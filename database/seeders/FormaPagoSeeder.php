<?php

namespace Database\Seeders;

use App\Models\FormaPago;
use Illuminate\Database\Seeder;

class FormaPagoSeeder extends Seeder
{
    public function run(): void
    {
        $formasPago = [
            [
                'codigo' => 'CONTADO',
                'nombre' => 'Contado',
                'tipo' => 'contado',
                'activo' => true,
                'descripcion' => 'Pago en efectivo al momento de la entrega',
            ],
            [
                'codigo' => 'TARJETA',
                'nombre' => 'Tarjeta de Crédito/Débito',
                'tipo' => 'tarjeta',
                'activo' => true,
                'descripcion' => 'Pago con tarjeta de crédito o débito',
            ],
            [
                'codigo' => 'TRANSFERENCIA',
                'nombre' => 'Transferencia Bancaria',
                'tipo' => 'transferencia',
                'activo' => true,
                'descripcion' => 'Pago por transferencia bancaria',
            ],
            [
                'codigo' => 'EFECTIVO',
                'nombre' => 'Efectivo',
                'tipo' => 'efectivo',
                'activo' => true,
                'descripcion' => 'Pago en efectivo',
            ],
            [
                'codigo' => 'PAGARE',
                'nombre' => 'Pagaré',
                'tipo' => 'pagare',
                'activo' => true,
                'descripcion' => 'Pago mediante pagaré',
            ],
            [
                'codigo' => 'RECIBO',
                'nombre' => 'Recibo Bancario',
                'tipo' => 'recibo_bancario',
                'activo' => true,
                'descripcion' => 'Domiciliación bancaria',
            ],
        ];

        foreach ($formasPago as $forma) {
            FormaPago::firstOrCreate(
                ['codigo' => $forma['codigo']],
                $forma
            );
        }
    }
}
