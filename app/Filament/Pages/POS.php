<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Services\POSService;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class POS extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static string $view = 'filament.pages.pos';
    protected static ?string $title = 'Punto de Venta (POS)';
    protected static ?string $slug = 'pos';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?int $navigationSort = 1;

    public $pendingSessions = [];
    public $currentSessionId = null;
    public $cartBookings = []; 
    
    public $categories = [];
    public $activeCategoryId = null;
    public $availableVehicles = [];
    
    public $customers = [];
    public $selectedCustomerId = null;
    
    public $paymentMethod = 'tarjeta';
    public $amountPaid = 0;

    public $cartSubtotal = 0;
    public $cartTax = 0;
    public $cartTotal = 0;

    public function mount()
    {
        $this->loadPendingSessions();
        $this->loadCategories();
        $this->loadAvailableVehicles();
        $this->customers = Customer::limit(50)->get();
    }

    protected function getPosService(): POSService
    {
        return app(POSService::class);
    }

    public function loadCategories()
    {
        $this->categories = \App\Models\VehicleCategory::where('is_active', true)->get();
    }

    public function loadPendingSessions()
    {
        $this->pendingSessions = $this->getPosService()->getPendingSessions();
    }

    public function loadAvailableVehicles()
    {
        $query = Vehicle::with(['category', 'images'])
            ->where('is_active', true)
            ->where('is_available', true);

        if ($this->activeCategoryId) {
            $query->where('category_id', $this->activeCategoryId);
        }

        $this->availableVehicles = $query->get();
    }

    public function filterByCategory($categoryId)
    {
        $this->activeCategoryId = $categoryId;
        $this->loadAvailableVehicles();
    }

    public function selectSession($sessionId)
    {
        $this->currentSessionId = $sessionId;
        $this->reloadCart();
    }

    public function createNewSession()
    {
        $this->currentSessionId = $this->getPosService()->createSession();
        $this->cartBookings = [];
        $this->selectedCustomerId = null;
        $this->recalculateCartTotals();
    }

    public function addVehicle($vehicleId)
    {
        if (!$this->currentSessionId) {
            $this->createNewSession();
        }

        $booking = $this->getPosService()->addVehicleToSession(
            $this->currentSessionId, 
            $vehicleId, 
            $this->selectedCustomerId, 
            auth()->id()
        );

        if ($booking) {
            $this->reloadCart();
            $this->loadPendingSessions();
            Notification::make()->title('Vehículo añadido')->success()->send();
        }
    }

    public function removeBooking($bookingId)
    {
        if ($this->getPosService()->removeBooking($this->currentSessionId, $bookingId)) {
            $this->reloadCart();
            $this->loadPendingSessions();
            
            if (count($this->cartBookings) === 0) {
                $this->clearCart();
            }
        }
    }

    public function updateDates($bookingId, $startDate, $endDate)
    {
        $booking = Booking::where('pos_session_id', $this->currentSessionId)->find($bookingId);
        if ($booking) {
            $this->getPosService()->updateBookingDates($booking, $startDate, $endDate);
            $this->reloadCart();
        }
    }

    public function updateCustomer($customerId)
    {
        $this->selectedCustomerId = $customerId ?: null;
        if ($this->currentSessionId) {
            $this->getPosService()->updateCustomerForSession($this->currentSessionId, $this->selectedCustomerId);
            $this->reloadCart();
            $this->loadPendingSessions();
        }
    }

    protected function reloadCart()
    {
        if ($this->currentSessionId) {
            $this->cartBookings = Booking::with(['vehicle'])
                ->where('pos_session_id', $this->currentSessionId)
                ->get();
                
            if ($this->cartBookings->count() > 0) {
                $first = $this->cartBookings->first();
                $this->selectedCustomerId = $first->customer_id;
            }
        } else {
            $this->cartBookings = [];
        }
        $this->recalculateCartTotals();
    }

    protected function recalculateCartTotals()
    {
        $this->cartSubtotal = collect($this->cartBookings)->sum('subtotal');
        $this->cartTax = collect($this->cartBookings)->sum('tax_amount');
        $this->cartTotal = collect($this->cartBookings)->sum('total_amount');
    }

    public function clearCart()
    {
        $this->currentSessionId = null;
        $this->cartBookings = [];
        $this->selectedCustomerId = null;
        $this->recalculateCartTotals();
        $this->loadPendingSessions();
    }

    public function completePayment()
    {
        if (!$this->currentSessionId || count($this->cartBookings) === 0) {
            Notification::make()->title('El carrito está vacío')->danger()->send();
            return;
        }

        $this->amountPaid = max((float)$this->amountPaid, (float)$this->cartTotal);
        
        $success = $this->getPosService()->checkoutSession($this->currentSessionId, $this->paymentMethod, $this->amountPaid);

        if ($success) {
            $this->dispatch('close-modal', id: 'payment-modal');
            Notification::make()->title('Cobro completado con éxito')->success()->send();
            $this->clearCart();
            $this->loadAvailableVehicles();
        }
    }

    public function cancelSession()
    {
        if ($this->currentSessionId) {
            $this->getPosService()->cancelSession($this->currentSessionId);
            $this->clearCart();
            Notification::make()->title('Ticket anulado')->success()->send();
        }
    }
}
