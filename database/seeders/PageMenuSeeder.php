<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\Menu;
use App\Models\MenuItem;

class PageMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data to avoid foreign key issues
        \Illuminate\Support\Facades\DB::table('menu_items')->delete();
        \Illuminate\Support\Facades\DB::table('menu_items')->where('menu_id', 0)->delete();
        \Illuminate\Support\Facades\DB::table('pages')->delete();
        \Illuminate\Support\Facades\DB::table('menus')->delete();

        // ============================================================
        // PAGES DATA
        // ============================================================
        $pages = [
            // 1. INICIO
            [
                'title' => 'Inicio',
                'slug' => 'inicio',
                'excerpt' => 'Alquiler de scooters y vehículos en Ibiza con la mejor relación calidad-precio. Especialistas en motos y scooters para recorrer la isla.',
                'content' => '',
                'template' => 'inicio',
                'status' => 'published',
                'layout' => 'layouts.app',
                'meta_title' => 'Extrarent - Alquiler de Motos y Scooters en Ibiza',
                'meta_description' => 'Alquila tu scooter o moto en Ibiza con Extrarent. Especialistas en alquiler de scooters y motos en Ibiza Puerto. Las mejores marcas: SYM, Piaggio, Vespa.',
                'published' => true,
                'in_menu' => true,
                'menu_order' => 0,
            ],

            // 2. VEHÍCULOS
            [
                'title' => 'Vehículos',
                'slug' => 'vehiculos',
                'excerpt' => 'Descubre nuestra flota de scooters y motos disponibles para alquiler en Ibiza. SYM, Piaggio y Vespa.',
                'content' => '
<div style="background: linear-gradient(135deg, #161829 0%, #292D45 100%); padding: 80px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px; text-align: center;">
        <h1 style="color: #ffffff; font-size: 42px; font-weight: 700; margin-bottom: 16px; line-height: 1.2;">Nuestros Vehículos</h1>
        <p style="color: #a0a4b7; font-size: 18px; max-width: 700px; margin: 0 auto; line-height: 1.6;">Especialistas en alquiler de scooters y motos en Ibiza. Elige el tuyo y descubre la isla con total libertad.</p>
    </div>
</div>

<div style="max-width: 1200px; margin: 0 auto; padding: 60px 20px;">
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: -40px;">

        <!-- Card 1: SYM Symphony 125cc -->
        <div style="background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); transition: transform 0.3s ease, box-shadow 0.3s ease;">
            <div style="position: relative; overflow: hidden;">
                <img src="/images/vehicles/sym-symphony-125.jpg" alt="SYM Symphony 125cc" style="width: 100%; height: 240px; object-fit: cover; display: block;">
                <div style="position: absolute; top: 16px; right: 16px; background: #4ade80; color: #065f46; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600;">Disponible</div>
            </div>
            <div style="padding: 24px;">
                <h3 style="font-size: 22px; font-weight: 700; color: #161829; margin-bottom: 16px;">SYM Symphony 125cc</h3>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 500;">Cilindrada</td>
                        <td style="padding: 10px 0; color: #161829; font-size: 14px; text-align: right; font-weight: 600;">125cc</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 500;">Potencia</td>
                        <td style="padding: 10px 0; color: #161829; font-size: 14px; text-align: right; font-weight: 600;">11 CV</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 500;">Asientos</td>
                        <td style="padding: 10px 0; color: #161829; font-size: 14px; text-align: right; font-weight: 600;">2 personas</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 500;">Depósito</td>
                        <td style="padding: 10px 0; color: #161829; font-size: 14px; text-align: right; font-weight: 600;">10.5 L</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 500;">Caja</td>
                        <td style="padding: 10px 0; color: #161829; font-size: 14px; text-align: right; font-weight: 600;">Automática</td>
                    </tr>
                </table>
                <div style="display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 20px;">
                    <div>
                        <span style="font-size: 32px; font-weight: 800; color: #161829;">60€</span>
                        <span style="color: #64748b; font-size: 15px;">/día</span>
                    </div>
                    <a href="/pages/reservas" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #ffffff; padding: 12px 28px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 15px; transition: opacity 0.2s ease;">Reservar Ahora</a>
                </div>
            </div>
        </div>

        <!-- Card 2: Piaggio Medley 125cc -->
        <div style="background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); transition: transform 0.3s ease, box-shadow 0.3s ease;">
            <div style="position: relative; overflow: hidden;">
                <img src="/images/vehicles/medley-125.jpg" alt="Piaggio Medley 125cc" style="width: 100%; height: 240px; object-fit: cover; display: block;">
                <div style="position: absolute; top: 16px; right: 16px; background: #4ade80; color: #065f46; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600;">Disponible</div>
            </div>
            <div style="padding: 24px;">
                <h3 style="font-size: 22px; font-weight: 700; color: #161829; margin-bottom: 16px;">Piaggio Medley 125cc</h3>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 500;">Cilindrada</td>
                        <td style="padding: 10px 0; color: #161829; font-size: 14px; text-align: right; font-weight: 600;">125cc</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 500;">Potencia</td>
                        <td style="padding: 10px 0; color: #161829; font-size: 14px; text-align: right; font-weight: 600;">12 CV</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 500;">Asientos</td>
                        <td style="padding: 10px 0; color: #161829; font-size: 14px; text-align: right; font-weight: 600;">2 personas</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 500;">Depósito</td>
                        <td style="padding: 10px 0; color: #161829; font-size: 14px; text-align: right; font-weight: 600;">9.5 L</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 500;">Caja</td>
                        <td style="padding: 10px 0; color: #161829; font-size: 14px; text-align: right; font-weight: 600;">Automática</td>
                    </tr>
                </table>
                <div style="display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 20px;">
                    <div>
                        <span style="font-size: 32px; font-weight: 800; color: #161829;">70€</span>
                        <span style="color: #64748b; font-size: 15px;">/día</span>
                    </div>
                    <a href="/pages/reservas" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #ffffff; padding: 12px 28px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 15px; transition: opacity 0.2s ease;">Reservar Ahora</a>
                </div>
            </div>
        </div>

        <!-- Card 3: Vespa Primavera 125 -->
        <div style="background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); transition: transform 0.3s ease, box-shadow 0.3s ease;">
            <div style="position: relative; overflow: hidden;">
                <img src="/images/vehicles/vespa-primavera-125.jpg" alt="Vespa Primavera 125" style="width: 100%; height: 240px; object-fit: cover; display: block;">
                <div style="position: absolute; top: 16px; right: 16px; background: #4ade80; color: #065f46; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600;">Disponible</div>
            </div>
            <div style="padding: 24px;">
                <h3 style="font-size: 22px; font-weight: 700; color: #161829; margin-bottom: 16px;">Vespa Primavera 125</h3>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 500;">Cilindrada</td>
                        <td style="padding: 10px 0; color: #161829; font-size: 14px; text-align: right; font-weight: 600;">125cc</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 500;">Potencia</td>
                        <td style="padding: 10px 0; color: #161829; font-size: 14px; text-align: right; font-weight: 600;">11 CV</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 500;">Asientos</td>
                        <td style="padding: 10px 0; color: #161829; font-size: 14px; text-align: right; font-weight: 600;">2 personas</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 500;">Depósito</td>
                        <td style="padding: 10px 0; color: #161829; font-size: 14px; text-align: right; font-weight: 600;">7.5 L</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 500;">Caja</td>
                        <td style="padding: 10px 0; color: #161829; font-size: 14px; text-align: right; font-weight: 600;">Automática</td>
                    </tr>
                </table>
                <div style="display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 20px;">
                    <div>
                        <span style="font-size: 32px; font-weight: 800; color: #161829;">70€</span>
                        <span style="color: #64748b; font-size: 15px;">/día</span>
                    </div>
                    <a href="/pages/reservas" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #ffffff; padding: 12px 28px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 15px; transition: opacity 0.2s ease;">Reservar Ahora</a>
                </div>
            </div>
        </div>

    </div>

    <div style="text-align: center; margin-top: 50px; padding: 40px; background: #f8fafc; border-radius: 16px;">
        <h3 style="font-size: 24px; font-weight: 700; color: #161829; margin-bottom: 12px;">¿Necesitas más información?</h3>
        <p style="color: #64748b; font-size: 16px; margin-bottom: 24px;">Contáctanos y te asesoramos sobre el vehículo que mejor se adapta a tus necesidades.</p>
        <a href="/pages/contactar" style="display: inline-block; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #ffffff; padding: 14px 36px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 16px;">Contactar con Nosotros</a>
    </div>
</div>
                ',
                'template' => 'default',
                'status' => 'published',
                'layout' => 'layouts.app',
                'meta_title' => 'Alquiler de Motos y Scooters en Ibiza - Extrarent',
                'meta_description' => 'Scooters SYM Symphony, Piaggio Medley y Vespa Primavera 125cc para alquiler en Ibiza. Desde 60€/día. Reserva online.',
                'published' => true,
                'in_menu' => true,
                'menu_order' => 1,
            ],

            // 3. RESERVAS
            [
                'title' => 'Reservas',
                'slug' => 'reservas',
                'excerpt' => 'Reserva tu scooter o moto online de forma rápida y sencilla. Selecciona fecha, vehículo y disfruta de Ibiza.',
                'content' => '
<div style="background: linear-gradient(135deg, #161829 0%, #292D45 100%); padding: 80px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px; text-align: center;">
        <h1 style="color: #ffffff; font-size: 42px; font-weight: 700; margin-bottom: 16px; line-height: 1.2;">Reserva tu Vehículo</h1>
        <p style="color: #a0a4b7; font-size: 18px; max-width: 700px; margin: 0 auto; line-height: 1.6;">Completa el formulario y nos pondremos en contacto contigo para confirmar tu reserva.</p>
    </div>
</div>

<div style="max-width: 800px; margin: 0 auto; padding: 60px 20px;">
    <div style="background: #ffffff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
        <h2 style="font-size: 24px; font-weight: 700; color: #161829; margin-bottom: 30px; text-align: center;">Solicitud de Reserva</h2>

        <form style="display: grid; gap: 24px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Fecha de recogida</label>
                    <input type="date" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                </div>
                <div>
                    <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Fecha de devolución</label>
                    <input type="date" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Hora de recogida</label>
                    <select style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                        <option value="">Seleccionar hora</option>
                        <option value="08:00">08:00</option>
                        <option value="09:00">09:00</option>
                        <option value="10:00">10:00</option>
                        <option value="11:00">11:00</option>
                        <option value="12:00">12:00</option>
                        <option value="13:00">13:00</option>
                        <option value="14:00">14:00</option>
                        <option value="15:00">15:00</option>
                        <option value="16:00">16:00</option>
                        <option value="17:00">17:00</option>
                        <option value="18:00">18:00</option>
                        <option value="19:00">19:00</option>
                        <option value="20:00">20:00</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Hora de devolución</label>
                    <select style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                        <option value="">Seleccionar hora</option>
                        <option value="08:00">08:00</option>
                        <option value="09:00">09:00</option>
                        <option value="10:00">10:00</option>
                        <option value="11:00">11:00</option>
                        <option value="12:00">12:00</option>
                        <option value="13:00">13:00</option>
                        <option value="14:00">14:00</option>
                        <option value="15:00">15:00</option>
                        <option value="16:00">16:00</option>
                        <option value="17:00">17:00</option>
                        <option value="18:00">18:00</option>
                        <option value="19:00">19:00</option>
                        <option value="20:00">20:00</option>
                    </select>
                </div>
            </div>

            <div>
                <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Vehículo</label>
                <select style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                    <option value="">Seleccionar vehículo</option>
                    <option value="sym-symphony-125">SYM Symphony 125cc - 60€/día</option>
                    <option value="piaggio-medley-125">Piaggio Medley 125cc - 70€/día</option>
                    <option value="vespa-primavera-125">Vespa Primavera 125 - 70€/día</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Nombre completo</label>
                    <input type="text" placeholder="Tu nombre" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                </div>
                <div>
                    <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Email</label>
                    <input type="email" placeholder="tu@email.com" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Teléfono</label>
                    <input type="tel" placeholder="+34 600 000 000" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                </div>
                <div>
                    <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Número de licencia</label>
                    <input type="text" placeholder="Tu número de licencia" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                </div>
            </div>

            <div>
                <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Comentarios adicionales</label>
                <textarea rows="4" placeholder="¿Alguna consulta o comentario?" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb; resize: vertical;"></textarea>
            </div>

            <div style="text-align: center; margin-top: 10px;">
                <button type="submit" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #ffffff; padding: 16px 48px; border: none; border-radius: 10px; font-weight: 700; font-size: 17px; cursor: pointer; width: 100%;">Enviar Solicitud de Reserva</button>
            </div>
        </form>
    </div>

    <div style="margin-top: 40px; background: #f8fafc; border-radius: 16px; padding: 30px;">
        <h3 style="font-size: 20px; font-weight: 700; color: #161829; margin-bottom: 16px;">Modificar o cancelar reserva</h3>
        <p style="color: #64748b; font-size: 15px; line-height: 1.7; margin-bottom: 16px;">Si necesitas modificar o cancelar tu reserva, por favor contáctanos lo antes posible a través de:</p>
        <p style="color: #64748b; font-size: 15px; line-height: 1.7;"><strong>Teléfono / WhatsApp:</strong> <a href="tel:+34671183514" style="color: #6366f1; text-decoration: none;">+34 671 18 35 14</a></p>
        <p style="color: #64748b; font-size: 15px; line-height: 1.7;"><strong>Email:</strong> <a href="mailto:info@extrarent.es" style="color: #6366f1; text-decoration: none;">info@extrarent.es</a></p>
    </div>
</div>
                ',
                'template' => 'default',
                'status' => 'published',
                'layout' => 'layouts.app',
                'meta_title' => 'Reservar Scooter o Moto en Ibiza - Extrarent',
                'meta_description' => 'Reserva online tu scooter o moto en Ibiza. Proceso rápido y sencillo. SYM, Piaggio y Vespa disponibles. Desde 60€/día.',
                'published' => true,
                'in_menu' => true,
                'menu_order' => 2,
            ],

            // 4. NOSOTROS
            [
                'title' => 'Sobre Nosotros',
                'slug' => 'nosotros',
                'excerpt' => 'Más de 33 años de experiencia en el alquiler de scooters y motos en Ibiza. Especialistas en dar el mejor servicio a nuestros clientes.',
                'content' => '
<div style="background: linear-gradient(135deg, #161829 0%, #292D45 100%); padding: 80px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px; text-align: center;">
        <h1 style="color: #ffffff; font-size: 42px; font-weight: 700; margin-bottom: 16px; line-height: 1.2;">Sobre Nosotros</h1>
        <p style="color: #a0a4b7; font-size: 18px; max-width: 700px; margin: 0 auto; line-height: 1.6;">Más de tres décadas ofreciendo el mejor servicio de alquiler de scooters y motos en Ibiza.</p>
    </div>
</div>

<!-- ¿Por qué nosotros? -->
<div style="max-width: 1200px; margin: 0 auto; padding: 80px 20px;">
    <div style="text-align: center; margin-bottom: 60px;">
        <h2 style="font-size: 36px; font-weight: 700; color: #161829; margin-bottom: 20px;">¿Por qué nosotros?</h2>
        <p style="color: #64748b; font-size: 18px; max-width: 700px; margin: 0 auto; line-height: 1.7;">En Extrarent llevamos más de 33 años siendo referentes en el alquiler de scooters y motos en Ibiza. Nuestro compromiso es ofrecerte la mejor experiencia desde el primer momento.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-bottom: 80px;">
        <div style="text-align: center; padding: 30px;">
            <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <svg width="28" height="28" fill="none" stroke="#ffffff" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <h3 style="font-size: 20px; font-weight: 700; color: #161829; margin-bottom: 12px;">Calidad Garantizada</h3>
            <p style="color: #64748b; font-size: 15px; line-height: 1.7;">Todos nuestros vehículos están en perfecto estado, revisados y listos para que disfrutes de Ibiza sin preocupaciones.</p>
        </div>
        <div style="text-align: center; padding: 30px;">
            <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <svg width="28" height="28" fill="none" stroke="#ffffff" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10.783V4.97c0-.784-.625-1.41-1.409-1.41H13.53l-2.6-1.953a.8-.8 0 00-.49-.157H6.406c-.783 0-1.406.626-1.406 1.41v14.04c0 .784.623 1.41 1.406 1.41h3.19c.176 0 .345-.058.49-.157l2.6-1.953h6.062c.784 0 1.409-.626 1.409-1.41v-4.813l-6.363 4.545z"/></svg>
            </div>
            <h3 style="font-size: 20px; font-weight: 700; color: #161829; margin-bottom: 12px;">Mejor Precio</h3>
            <p style="color: #64748b; font-size: 15px; line-height: 1.7;">Ofrecemos tarifas competitivas sin renunciar a la calidad. El mejor precio para tu alquiler en Ibiza.</p>
        </div>
        <div style="text-align: center; padding: 30px;">
            <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <svg width="28" height="28" fill="none" stroke="#ffffff" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.5z"/></svg>
            </div>
            <h3 style="font-size: 20px; font-weight: 700; color: #161829; margin-bottom: 12px;">Recogida en Puerto</h3>
            <p style="color: #64748b; font-size: 15px; line-height: 1.7;">Te esperamos en el Puerto de Ibiza para que empieces tus vacaciones directamente sobre la moto.</p>
        </div>
    </div>

    <!-- Quiénes somos -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; margin-bottom: 80px;">
        <div>
            <img src="/images/nosotros/nueva_fachada-1024x705.jpg" alt="Extrarent Ibiza" style="width: 100%; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.1);">
        </div>
        <div>
            <h2 style="font-size: 32px; font-weight: 700; color: #161829; margin-bottom: 20px;">Nuestra Historia</h2>
            <p style="color: #64748b; font-size: 16px; line-height: 1.8; margin-bottom: 16px;">Extrarent nació hace más de 33 años como un proyecto familiar con una idea clara: ofrecer un servicio de alquiler de scooters y motos en Ibiza diferente, cercano y de calidad.</p>
            <p style="color: #64748b; font-size: 16px; line-height: 1.8; margin-bottom: 16px;">Desde entonces, hemos ido creciendo de forma constante, siempre apostando por la renovación de nuestra flota y por mantener la cercanía y el trato personalizado que nos caracteriza.</p>
            <p style="color: #64748b; font-size: 16px; line-height: 1.8;">Hoy en día, somos especialistas en alquiler de scooters y motos en Ibiza, con una flota moderna de las mejores marcas: SYM, Piaggio y Vespa.</p>
        </div>
    </div>

    <!-- Galería de imágenes -->
    <div style="text-align: center; margin-bottom: 50px;">
        <h2 style="font-size: 32px; font-weight: 700; color: #161829; margin-bottom: 40px;">Nuestro Equipo</h2>
    </div>
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 80px;">
        <div>
            <img src="/images/nosotros/45_aniversario_Extrarent.jpg" alt="Extrarent 45 aniversario" style="width: 100%; height: 280px; object-fit: cover; border-radius: 12px; display: block;">
        </div>
        <div>
            <img src="/images/nosotros/Rafa1-1024x987.jpg" alt="Equipo Extrarent" style="width: 100%; height: 280px; object-fit: cover; border-radius: 12px; display: block;">
        </div>
        <div>
            <img src="/images/nosotros/extrrent-especialista-alquiler-motos-ibiza.jpg" alt="Extrarent motos Ibiza" style="width: 100%; height: 280px; object-fit: cover; border-radius: 12px; display: block;">
        </div>
        <div>
            <img src="/images/nosotros/nueva_fachada-1024x705.jpg" alt="Local Extrarent Ibiza" style="width: 100%; height: 280px; object-fit: cover; border-radius: 12px; display: block;">
        </div>
    </div>

    <!-- Estadísticas -->
    <div style="background: linear-gradient(135deg, #161829 0%, #292D45 100%); border-radius: 20px; padding: 60px 40px; margin-bottom: 80px;">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; text-align: center;">
            <div>
                <div style="font-size: 48px; font-weight: 800; color: #ffffff; margin-bottom: 8px;">33+</div>
                <div style="color: #a0a4b7; font-size: 16px;">Años de Experiencia</div>
            </div>
            <div>
                <div style="font-size: 48px; font-weight: 800; color: #ffffff; margin-bottom: 8px;">100%</div>
                <div style="color: #a0a4b7; font-size: 16px;">Compromiso con el Cliente</div>
            </div>
            <div>
                <div style="font-size: 48px; font-weight: 800; color: #ffffff; margin-bottom: 8px;">3</div>
                <div style="color: #a0a4b7; font-size: 16px;">Modelos de Scooter</div>
            </div>
        </div>
    </div>

    <!-- Testimonios -->
    <div style="text-align: center; margin-bottom: 50px;">
        <h2 style="font-size: 32px; font-weight: 700; color: #161829; margin-bottom: 12px;">Lo que dicen nuestros clientes</h2>
        <p style="color: #64748b; font-size: 16px;">La satisfacción de nuestros clientes es nuestra mejor carta de presentación.</p>
    </div>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">
        <div style="background: #f8fafc; border-radius: 16px; padding: 30px;">
            <div style="display: flex; margin-bottom: 16px;">
                <span style="color: #f59e0b; font-size: 20px;">★★★★★</span>
            </div>
            <p style="color: #4b5563; font-size: 15px; line-height: 1.7; margin-bottom: 20px; font-style: italic;">"Excelente servicio. Nos entregaron la moto en el puerto y todo perfecto. Los vehículos están en muy buen estado. Repetiremos seguro."</p>
            <p style="color: #161829; font-weight: 600; font-size: 15px;">María G.</p>
            <p style="color: #64748b; font-size: 13px;">Madrid, España</p>
        </div>
        <div style="background: #f8fafc; border-radius: 16px; padding: 30px;">
            <div style="display: flex; margin-bottom: 16px;">
                <span style="color: #f59e0b; font-size: 20px;">★★★★★</span>
            </div>
            <p style="color: #4b5563; font-size: 15px; line-height: 1.7; margin-bottom: 20px; font-style: italic;">"Great value for money. The Piaggio Medley was in perfect condition. The team at Extrarent were very friendly and helpful. Highly recommended!"</p>
            <p style="color: #161829; font-weight: 600; font-size: 15px;">James T.</p>
            <p style="color: #64748b; font-size: 13px;">London, UK</p>
        </div>
        <div style="background: #f8fafc; border-radius: 16px; padding: 30px;">
            <div style="display: flex; margin-bottom: 16px;">
                <span style="color: #f59e0b; font-size: 20px;">★★★★★</span>
            </div>
            <p style="color: #4b5563; font-size: 15px; line-height: 1.7; margin-bottom: 20px; font-style: italic;">"La Vespa Primavera estaba como nueva. El trato del personal inmejorable. Sin duda la mejor opción para alquilar una moto en Ibiza."</p>
            <p style="color: #161829; font-weight: 600; font-size: 15px;">Carlos R.</p>
            <p style="color: #64748b; font-size: 13px;">Barcelona, España</p>
        </div>
    </div>
</div>
                ',
                'template' => 'default',
                'status' => 'published',
                'layout' => 'layouts.app',
                'meta_title' => 'Sobre Extrarent - Historia y valores del alquiler de motos en Ibiza',
                'meta_description' => 'Conoce la historia de Extrarent: más de 33 años de experiencia en alquiler de scooters y motos en Ibiza. Calidad, compromiso y los mejores precios.',
                'published' => true,
                'in_menu' => true,
                'menu_order' => 3,
            ],

            // 5. FAQ
            [
                'title' => 'Preguntas Frecuentes',
                'slug' => 'faq',
                'excerpt' => 'Resuelve todas tus dudas sobre el alquiler de scooters y motos en Ibiza con Extrarent.',
                'content' => '
<div style="background: linear-gradient(135deg, #161829 0%, #292D45 100%); padding: 80px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px; text-align: center;">
        <h1 style="color: #ffffff; font-size: 42px; font-weight: 700; margin-bottom: 16px; line-height: 1.2;">Preguntas Frecuentes</h1>
        <p style="color: #a0a4b7; font-size: 18px; max-width: 700px; margin: 0 auto; line-height: 1.6;">Resolvemos las dudas más habituales de nuestros clientes sobre el alquiler de scooters y motos en Ibiza.</p>
    </div>
</div>

<div style="max-width: 800px; margin: 0 auto; padding: 60px 20px;">

    <!-- FAQ 1 -->
    <div style="background: #ffffff; border-radius: 16px; padding: 28px 32px; margin-bottom: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.05);">
        <h3 style="font-size: 18px; font-weight: 700; color: #161829; margin-bottom: 12px;">¿Qué ocurre si pierdo accesorios (cascos, maleta, etc.)?</h3>
        <p style="color: #64748b; font-size: 15px; line-height: 1.7;">En caso de pérdida de accesorios como cascos, maleta, tapas o cualquier otro equipamiento que te facilitamos, deberás abonar el coste de reposación del mismo. Los precios orientativos son:</p>
        <ul style="color: #64748b; font-size: 15px; line-height: 1.8; margin-top: 12px; padding-left: 20px;">
            <li>Casco integral: 45€</li>
            <li>Maleta superior: 30€</li>
            <li>Tapas de depósito: 25€</li>
            <li>Chubasquero: 15€</li>
        </ul>
        <p style="color: #64748b; font-size: 15px; line-height: 1.7; margin-top: 12px;">Te recomendamos cuidar todos los accesorios y consultarnos cualquier duda antes de utilizarlos.</p>
    </div>

    <!-- FAQ 2 -->
    <div style="background: #ffffff; border-radius: 16px; padding: 28px 32px; margin-bottom: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.05);">
        <h3 style="font-size: 18px; font-weight: 700; color: #161829; margin-bottom: 12px;">¿Qué es la franquicia de 200€?</h3>
        <p style="color: #64748b; font-size: 15px; line-height: 1.7;">La franquicia de 200€ es un depósito de garantía que se solicita al inicio del alquiler. Este importe se utiliza en caso de:</p>
        <ul style="color: #64748b; font-size: 15px; line-height: 1.8; margin-top: 12px; padding-left: 20px;">
            <li>Daños en el vehículo no cubiertos por el seguro</li>
            <li>Multas de tráfico durante el período de alquiler</li>
            <li>Pérdida o daño de accesorios</li>
            <li>Retraso en la devolución del vehículo</li>
        </ul>
        <p style="color: #64748b; font-size: 15px; line-height: 1.7; margin-top: 12px;">Si al devolver el vehículo todo está en orden, la franquicia se devuelve íntegramente. Aceptamos pago en efectivo o tarjeta de crédito/débito.</p>
    </div>

    <!-- FAQ 3 -->
    <div style="background: #ffffff; border-radius: 16px; padding: 28px 32px; margin-bottom: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.05);">
        <h3 style="font-size: 18px; font-weight: 700; color: #161829; margin-bottom: 12px;">¿Puedo recoger y devolver la moto en el Puerto de Ibiza?</h3>
        <p style="color: #64748b; font-size: 15px; line-height: 1.7;">¡Sí! Ofrecemos el servicio de entrega y recogida en el Puerto de Ibiza (Port d\'Eivissa). Es la forma más cómoda de empezar tus vacaciones:</p>
        <ul style="color: #64748b; font-size: 15px; line-height: 1.8; margin-top: 12px; padding-left: 20px;">
            <li>Te esperamos en el punto acordado del puerto</li>
            <li>Te explicamos el funcionamiento del vehículo</li>
            <li>Comprobamos documentación y firmamos el contrato</li>
            <li>La devolución también puede realizarse en el mismo punto</li>
        </ul>
        <p style="color: #64748b; font-size: 15px; line-height: 1.7; margin-top: 12px;">Este servicio está incluido en el precio sin coste adicional. Solo necesitas indicarnos tu hora de llegada al puerto cuando realices la reserva.</p>
    </div>

    <!-- FAQ 4 -->
    <div style="background: #ffffff; border-radius: 16px; padding: 28px 32px; margin-bottom: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.05);">
        <h3 style="font-size: 18px; font-weight: 700; color: #161829; margin-bottom: 12px;">¿Qué documentación necesito para alquilar?</h3>
        <p style="color: #64748b; font-size: 15px; line-height: 1.7;">Para alquilar un scooter o moto necesitarás:</p>
        <ul style="color: #64748b; font-size: 15px; line-height: 1.8; margin-top: 12px; padding-left: 20px;">
            <li><strong>Licencia de conducir:</strong> Permiso A1, A2 o A (según la cilindrada). También se acepta licencia de moto válida en la UE.</li>
            <li><strong>Documento de identidad:</strong> DNI o pasaporte en vigor.</li>
            <li><strong>Tarjeta de crédito o depósito en efectivo:</strong> Para la franquicia de garantía de 200€.</li>
            <li><strong>Edad mínima:</strong> 21 años para scooters de 125cc.</li>
        </ul>
        <p style="color: #64748b; font-size: 15px; line-height: 1.7; margin-top: 12px;">Es obligatorio tener la licencia de moto con una antigüedad mínima de 2 años.</p>
    </div>

    <!-- FAQ 5 -->
    <div style="background: #ffffff; border-radius: 16px; padding: 28px 32px; margin-bottom: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.05);">
        <h3 style="font-size: 18px; font-weight: 700; color: #161829; margin-bottom: 12px;">¿Cuáles son las obligaciones legales del conductor?</h3>
        <p style="color: #64748b; font-size: 15px; line-height: 1.7;">Por ley, al circular por Ibiza con un scooter o moto alquilado debes cumplir los siguientes requisitos:</p>
        <ul style="color: #64748b; font-size: 15px; line-height: 1.8; margin-top: 12px; padding-left: 20px;">
            <li><strong>Casco obligatorio:</strong> Tanto el conductor como el pasajero deben llevar casco homologado y abrochado en todo momento.</li>
            <li><strong>Seguro a terceros:</strong> Todos nuestros vehículos incluyen seguro obligatorio.</li>
            <li><strong>Cinturón de seguridad:</strong> Obligatorio para el pasajero si el vehículo lo dispone.</li>
            <li><strong>Documento del vehículo:</strong> Lo facilitamos junto con la moto.</li>
            <li><strong>Licencia válida:</strong> Debes llevar tu permiso de conducir de moto siempre contigo.</li>
        </ul>
        <p style="color: #64748b; font-size: 15px; line-height: 1.7; margin-top: 12px;">Todos nuestros scooters incluyen casco, seguro a terceros con franquicia, y documentación en regla. No tendrás ningún problema legal mientras circules correctamente.</p>
    </div>

    <!-- FAQ 6 -->
    <div style="background: #ffffff; border-radius: 16px; padding: 28px 32px; margin-bottom: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.05);">
        <h3 style="font-size: 18px; font-weight: 700; color: #161829; margin-bottom: 12px;">¿El alquiler incluye seguro?</h3>
        <p style="color: #64748b; font-size: 15px; line-height: 1.7;">Sí, todos nuestros vehículos incluyen seguro a terceros con franquicia de 200€ incluido en el precio. La franquicia cubre daños propios hasta 200€. Si deseas una cobertura total sin franquicia, consúltanos y te ofreceremos esta opción con un coste adicional reducido.</p>
    </div>

    <!-- FAQ 7 -->
    <div style="background: #ffffff; border-radius: 16px; padding: 28px 32px; margin-bottom: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.05);">
        <h3 style="font-size: 18px; font-weight: 700; color: #161829; margin-bottom: 12px;">¿Cuánto tiempo necesito para devolver la moto?</h3>
        <p style="color: #64748b; font-size: 15px; line-height: 1.7;">Las horas de devolución son las acordadas en el contrato. Generalmente, el horario es de 8:30h a 20:00h. Si necesitas devolver la moto fuera de horario, consúltanos y haremos lo posible por adaptarnos a tus necesidades. Los retrasos superiores a 30 minutos sin avisar pueden implicar un cargo adicional.</p>
    </div>

    <div style="text-align: center; margin-top: 50px; padding: 40px; background: #f8fafc; border-radius: 16px;">
        <h3 style="font-size: 24px; font-weight: 700; color: #161829; margin-bottom: 12px;">¿Tienes más preguntas?</h3>
        <p style="color: #64748b; font-size: 16px; margin-bottom: 24px;">No dudes en contactarnos. Estaremos encantados de resolver cualquier duda que tengas.</p>
        <a href="/pages/contactar" style="display: inline-block; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #ffffff; padding: 14px 36px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 16px;">Contactar con Nosotros</a>
    </div>

</div>
                ',
                'template' => 'default',
                'status' => 'published',
                'layout' => 'layouts.app',
                'meta_title' => 'Preguntas Frecuentes - Alquiler de Motos en Ibiza | Extrarent',
                'meta_description' => 'Resuelve tus dudas sobre el alquiler de scooters en Ibiza: franquicia, documentación, seguro, recogida en puerto y más. Extrarent Ibiza.',
                'published' => true,
                'in_menu' => true,
                'menu_order' => 4,
            ],

            // 6. CONTACTAR
            [
                'title' => 'Contactar',
                'slug' => 'contactar',
                'excerpt' => 'Ponte en contacto con Extrarent. Estamos en el Puerto de Ibiza, en la Avinguda de Santa Eulària des Riu.',
                'content' => '
<div style="background: linear-gradient(135deg, #161829 0%, #292D45 100%); padding: 80px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px; text-align: center;">
        <h1 style="color: #ffffff; font-size: 42px; font-weight: 700; margin-bottom: 16px; line-height: 1.2;">Contactar</h1>
        <p style="color: #a0a4b7; font-size: 18px; max-width: 700px; margin: 0 auto; line-height: 1.6;">Estamos aquí para ayudarte. Contáctanos por teléfono, email, WhatsApp o visítanos en nuestra oficina.</p>
    </div>
</div>

<div style="max-width: 1200px; margin: 0 auto; padding: 60px 20px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px;">

        <!-- Info Column -->
        <div>
            <h2 style="font-size: 28px; font-weight: 700; color: #161829; margin-bottom: 30px;">Información de Contacto</h2>

            <!-- Dirección -->
            <div style="margin-bottom: 30px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #161829; margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                    <span style="display: inline-flex; width: 40px; height: 40px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 10px; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="18" height="18" fill="none" stroke="#ffffff" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </span>
                    Dirección
                </h3>
                <p style="color: #64748b; font-size: 15px; line-height: 1.7; padding-left: 50px;">Avinguda de Santa Eulària des Riu, 25<br>07800 Ibiza (Eivissa), Islas Baleares</p>
            </div>

            <!-- Horario -->
            <div style="margin-bottom: 30px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #161829; margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                    <span style="display: inline-flex; width: 40px; height: 40px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 10px; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="18" height="18" fill="none" stroke="#ffffff" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    </span>
                    Horario de Oficina
                </h3>
                <table style="padding-left: 50px; font-size: 15px;">
                    <tr>
                        <td style="padding: 6px 0; color: #64748b; padding-right: 20px;">Lunes - Domingo</td>
                        <td style="padding: 6px 0; color: #161829; font-weight: 600;">08:30 - 20:00</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #64748b; padding-right: 20px;">Temporada Alta (Julio-Agosto)</td>
                        <td style="padding: 6px 0; color: #161829; font-weight: 600;">08:00 - 21:00</td>
                    </tr>
                </table>
            </div>

            <!-- Teléfono y Email -->
            <div style="margin-bottom: 30px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #161829; margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                    <span style="display: inline-flex; width: 40px; height: 40px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 10px; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="18" height="18" fill="none" stroke="#ffffff" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                    </span>
                    Teléfono y WhatsApp
                </h3>
                <p style="color: #64748b; font-size: 15px; line-height: 1.7; padding-left: 50px;">
                    <a href="tel:+34671183514" style="color: #6366f1; text-decoration: none; font-weight: 600;">+34 671 18 35 14</a><br>
                    <a href="https://wa.me/34671183514" style="color: #25D366; text-decoration: none; font-weight: 600;">WhatsApp Directo</a>
                </p>
            </div>

            <div style="margin-bottom: 30px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #161829; margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                    <span style="display: inline-flex; width: 40px; height: 40px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 10px; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="18" height="18" fill="none" stroke="#ffffff" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="M22 6l-10 7L2 6"/></svg>
                    </span>
                    Email
                </h3>
                <p style="color: #64748b; font-size: 15px; line-height: 1.7; padding-left: 50px;">
                    <a href="mailto:info@extrarent.es" style="color: #6366f1; text-decoration: none;">info@extrarent.es</a>
                </p>
            </div>

            <!-- Redes Sociales -->
            <div>
                <h3 style="font-size: 18px; font-weight: 700; color: #161829; margin-bottom: 12px; display: flex; align-items: center; gap: 10px;">
                    <span style="display: inline-flex; width: 40px; height: 40px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 10px; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="18" height="18" fill="none" stroke="#ffffff" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 006 0V8"/><path d="M8 8v5a3 3 0 006 0V8"/><circle cx="12" cy="12" r="10"/></svg>
                    </span>
                    Redes Sociales
                </h3>
                <div style="display: flex; gap: 12px; padding-left: 50px;">
                    <a href="https://www.instagram.com/extrarent/" target="_blank" style="display: inline-flex; width: 44px; height: 44px; background: linear-gradient(135deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); border-radius: 10px; align-items: center; justify-content: center; text-decoration: none;">
                        <svg width="20" height="20" fill="#ffffff" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="https://www.facebook.com/extrarent" target="_blank" style="display: inline-flex; width: 44px; height: 44px; background: #1877F2; border-radius: 10px; align-items: center; justify-content: center; text-decoration: none;">
                        <svg width="20" height="20" fill="#ffffff" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Mapa -->
            <div style="margin-top: 30px; border-radius: 12px; overflow: hidden;">
                <img src="/images/contactar/25af06a8a22c16803a0bebbf0fb821a1bc115bc3-2-768x512.jpg" alt="Ubicación Extrarent Ibiza" style="width: 100%; height: 220px; object-fit: cover; border-radius: 12px; display: block;">
            </div>
        </div>

        <!-- Form Column -->
        <div>
            <h2 style="font-size: 28px; font-weight: 700; color: #161829; margin-bottom: 30px;">Envíanos un Mensaje</h2>
            <div style="background: #ffffff; border-radius: 16px; padding: 36px; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
                <form style="display: grid; gap: 20px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Nombre</label>
                            <input type="text" placeholder="Tu nombre" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                        </div>
                        <div>
                            <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Apellidos</label>
                            <input type="text" placeholder="Tus apellidos" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                        </div>
                    </div>
                    <div>
                        <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Email</label>
                        <input type="email" placeholder="tu@email.com" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                    </div>
                    <div>
                        <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Teléfono</label>
                        <input type="tel" placeholder="+34 600 000 000" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                    </div>
                    <div>
                        <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Asunto</label>
                        <select style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                            <option value="">Seleccionar asunto</option>
                            <option>Consulta sobre alquiler</option>
                            <option>Reserva</option>
                            <option>Modificación de reserva</option>
                            <option>Otro</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Mensaje</label>
                        <textarea rows="5" placeholder="Escribe tu mensaje aquí..." style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb; resize: vertical;"></textarea>
                    </div>
                    <div>
                        <button type="submit" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #ffffff; padding: 14px 36px; border: none; border-radius: 10px; font-weight: 700; font-size: 16px; cursor: pointer; width: 100%;">Enviar Mensaje</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
                ',
                'template' => 'default',
                'status' => 'published',
                'layout' => 'layouts.app',
                'meta_title' => 'Contactar con Extrarent - Alquiler de Motos en Ibiza',
                'meta_description' => 'Contacta con Extrarent: teléfono +34 671 18 35 14, email info@extrarent.es, WhatsApp, Instagram y Facebook. Oficina en Avinguda de Santa Eulària des Riu, 25, Ibiza.',
                'published' => true,
                'in_menu' => true,
                'menu_order' => 5,
            ],

            // 7. AVISO LEGAL
            [
                'title' => 'Aviso Legal',
                'slug' => 'aviso-legal',
                'excerpt' => 'Aviso legal y condiciones de uso del sitio web de Extrarent.',
                'content' => '
<div style="background: linear-gradient(135deg, #161829 0%, #292D45 100%); padding: 80px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px; text-align: center;">
        <h1 style="color: #ffffff; font-size: 42px; font-weight: 700; margin-bottom: 16px; line-height: 1.2;">Aviso Legal</h1>
    </div>
</div>
<div style="max-width: 800px; margin: 0 auto; padding: 60px 20px;">
    <div style="background: #ffffff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
        <h2 style="font-size: 22px; font-weight: 700; color: #161829; margin-bottom: 20px;">Datos Identificativos</h2>
        <p style="color: #64748b; font-size: 15px; line-height: 1.8; margin-bottom: 16px;">En cumplimiento del deber de información recogido en el artículo 10 de la Ley 34/2002, de 11 de julio, de Servicios de la Sociedad de la Información y del Comercio Electrónico (LSSI-CE), a continuación se reflechan los siguientes datos:</p>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 24px;">
            <tr style="border-bottom: 1px solid #f1f5f9;">
                <td style="padding: 12px 0; color: #64748b; font-size: 15px; font-weight: 500;">Titular:</td>
                <td style="padding: 12px 0; color: #161829; font-size: 15px;">Extrarent - Alquiler de Motos Ibiza</td>
            </tr>
            <tr style="border-bottom: 1px solid #f1f5f9;">
                <td style="padding: 12px 0; color: #64748b; font-size: 15px; font-weight: 500;">Domicilio:</td>
                <td style="padding: 12px 0; color: #161829; font-size: 15px;">Avinguda de Santa Eulària des Riu, 25, 07800 Ibiza</td>
            </tr>
            <tr style="border-bottom: 1px solid #f1f5f9;">
                <td style="padding: 12px 0; color: #64748b; font-size: 15px; font-weight: 500;">Email:</td>
                <td style="padding: 12px 0; color: #161829; font-size: 15px;">info@extrarent.es</td>
            </tr>
        </table>
        <h2 style="font-size: 22px; font-weight: 700; color: #161829; margin: 30px 0 20px;">Objeto</h2>
        <p style="color: #64748b; font-size: 15px; line-height: 1.8;">Este sitio web tiene como objeto proporcionar información sobre los servicios de alquiler de scooters y motos que Extrarent ofrece en la isla de Ibiza.</p>
        <h2 style="font-size: 22px; font-weight: 700; color: #161829; margin: 30px 0 20px;">Propiedad Intelectual</h2>
        <p style="color: #64748b; font-size: 15px; line-height: 1.8;">Todos los contenidos de este sitio web, incluyendo textos, imágenes, gráficos, logotipos y código fuente, son propiedad de Extrarent o de sus proveedores de contenido, y están protegidos por las leyes de propiedad intelectual e industrial.</p>
    </div>
</div>
                ',
                'template' => 'default',
                'status' => 'published',
                'layout' => 'layouts.app',
                'meta_title' => 'Aviso Legal - Extrarent Ibiza',
                'meta_description' => 'Aviso legal del sitio web de Extrarent. Información sobre datos identificativos, propiedad intelectual y condiciones de uso.',
                'published' => true,
                'in_menu' => false,
                'menu_order' => 0,
            ],

            // 8. PRIVACIDAD
            [
                'title' => 'Política de Privacidad',
                'slug' => 'privacidad',
                'excerpt' => 'Política de privacidad y protección de datos de Extrarent.',
                'content' => '
<div style="background: linear-gradient(135deg, #161829 0%, #292D45 100%); padding: 80px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px; text-align: center;">
        <h1 style="color: #ffffff; font-size: 42px; font-weight: 700; margin-bottom: 16px; line-height: 1.2;">Política de Privacidad</h1>
    </div>
</div>
<div style="max-width: 800px; margin: 0 auto; padding: 60px 20px;">
    <div style="background: #ffffff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
        <h2 style="font-size: 22px; font-weight: 700; color: #161829; margin-bottom: 20px;">Información sobre Protección de Datos</h2>
        <p style="color: #64748b; font-size: 15px; line-height: 1.8; margin-bottom: 16px;">En Extrarent nos comprometemos a proteger tu privacidad. Esta política de privacidad explica cómo recopilamos, utilizamos y protegemos tus datos personales conforme al Reglamento General de Protección de Datos (RGPD) y la Ley Orgánica 3/2018 de Protección de Datos Personales (LOPDGDD).</p>
        <h2 style="font-size: 22px; font-weight: 700; color: #161829; margin: 30px 0 20px;">Datos que Recopilamos</h2>
        <p style="color: #64748b; font-size: 15px; line-height: 1.8;">Recopilamos los datos personales que nos proporcionas de forma voluntaria a través de nuestros formularios de contacto, reservas o por correo electrónico. Estos datos pueden incluir: nombre, email, teléfono, fecha de nacimiento y datos de reserva.</p>
        <h2 style="font-size: 22px; font-weight: 700; color: #161829; margin: 30px 0 20px;">Finalidad del Tratamiento</h2>
        <p style="color: #64748b; font-size: 15px; line-height: 1.8;">Tus datos serán tratados con la finalidad de gestionar tus reservas, atender tus consultas, enviar información sobre nuestros servicios y mejorar la calidad de nuestros servicios.</p>
        <h2 style="font-size: 22px; font-weight: 700; color: #161829; margin: 30px 0 20px;">Tus Derechos</h2>
        <p style="color: #64748b; font-size: 15px; line-height: 1.8;">Tienes derecho a acceder a tus datos personales, rectificarlos, suprimirlos, oponerte a su tratamiento y solicitar la portabilidad de tus datos. Para ejercer estos derechos, puedes contactarnos en info@extrarent.es.</p>
    </div>
</div>
                ',
                'template' => 'default',
                'status' => 'published',
                'layout' => 'layouts.app',
                'meta_title' => 'Política de Privacidad - Extrarent Ibiza',
                'meta_description' => 'Política de privacidad de Extrarent. Información sobre cómo protegemos y tratamos tus datos personales conforme al RGPD.',
                'published' => true,
                'in_menu' => false,
                'menu_order' => 0,
            ],

            // 9. COOKIES
            [
                'title' => 'Política de Cookies',
                'slug' => 'cookies',
                'excerpt' => 'Política de cookies y uso de tecnologías de rastreo en el sitio web de Extrarent.',
                'content' => '
<div style="background: linear-gradient(135deg, #161829 0%, #292D45 100%); padding: 80px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px; text-align: center;">
        <h1 style="color: #ffffff; font-size: 42px; font-weight: 700; margin-bottom: 16px; line-height: 1.2;">Política de Cookies</h1>
    </div>
</div>
<div style="max-width: 800px; margin: 0 auto; padding: 60px 20px;">
    <div style="background: #ffffff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
        <h2 style="font-size: 22px; font-weight: 700; color: #161829; margin-bottom: 20px;">¿Qué son las Cookies?</h2>
        <p style="color: #64748b; font-size: 15px; line-height: 1.8; margin-bottom: 16px;">Las cookies son pequeños archivos de texto que se almacenan en tu dispositivo cuando visitas nuestro sitio web. Nos ayudan a ofrecerte una mejor experiencia de navegación y a mejorar nuestros servicios.</p>
        <h2 style="font-size: 22px; font-weight: 700; color: #161829; margin: 30px 0 20px;">Tipos de Cookies que Utilizamos</h2>
        <p style="color: #64748b; font-size: 15px; line-height: 1.8; margin-bottom: 16px;"><strong>Cookies técnicas:</strong> Son necesarias para el funcionamiento del sitio web y no requieren consentimiento.</p>
        <p style="color: #64748b; font-size: 15px; line-height: 1.8; margin-bottom: 16px;"><strong>Cookies analíticas:</strong> Nos permiten medir el número de visitantes y analizar cómo navegan por nuestro sitio web para mejorar nuestros servicios.</p>
        <p style="color: #64748b; font-size: 15px; line-height: 1.8; margin-bottom: 16px;"><strong>Cookies de personalización:</strong> Permiten que el usuario acceda al servicio con algunas características de carácter general predefinidas en función de una serie de criterios.</p>
        <h2 style="font-size: 22px; font-weight: 700; color: #161829; margin: 30px 0 20px;">Gestión de Cookies</h2>
        <p style="color: #64748b; font-size: 15px; line-height: 1.8;">Puedes configurar tu navegador para rechazar las cookies o para que te avise cuando un sitio web quiere establecer una cookie. Si desactivas las cookies, algunas funcionalidades del sitio web podrían no estar disponibles.</p>
    </div>
</div>
                ',
                'template' => 'default',
                'status' => 'published',
                'layout' => 'layouts.app',
                'meta_title' => 'Política de Cookies - Extrarent Ibiza',
                'meta_description' => 'Política de cookies de Extrarent. Información sobre el uso de cookies y cómo gestionarlas en tu navegador.',
                'published' => true,
                'in_menu' => false,
                'menu_order' => 0,
            ],
        ];

        // ============================================================
        // CREATE PAGES
        // ============================================================
        foreach ($pages as $pageData) {
            Page::create($pageData);
        }

        // ============================================================
        // MENUS
        // ============================================================
        $mainMenu = Menu::firstOrCreate(
            ['slug' => 'main'],
            [
                'name' => 'Menú Principal',
                'description' => 'Navegación principal del sitio',
            ]
        );

        $footerMenu = Menu::firstOrCreate(
            ['slug' => 'footer'],
            [
                'name' => 'Menú Footer',
                'description' => 'Enlaces del pie de página',
            ]
        );

        // ============================================================
        // MENU ITEMS - Menú Principal
        // ============================================================
        // Get page IDs after creation
        $inicioPageId = Page::where('slug', 'inicio')->first()->id;
        $vehiculosPageId = Page::where('slug', 'vehiculos')->first()->id;
        $reservasPageId = Page::where('slug', 'reservas')->first()->id;
        $nosotrosPageId = Page::where('slug', 'nosotros')->first()->id;
        $faqPageId = Page::where('slug', 'faq')->first()->id;
        $contactarPageId = Page::where('slug', 'contactar')->first()->id;

        $mainItems = [
            [
                'menu_id' => $mainMenu->id,
                'title' => 'Inicio',
                'type' => 'custom',
                'url' => '/',
                'menu_order' => 0,
            ],
            [
                'menu_id' => $mainMenu->id,
                'title' => 'Vehículos',
                'type' => 'page',
                'page_id' => $vehiculosPageId,
                'menu_order' => 1,
            ],
            [
                'menu_id' => $mainMenu->id,
                'title' => 'Reservas',
                'type' => 'page',
                'page_id' => $reservasPageId,
                'menu_order' => 2,
            ],
            [
                'menu_id' => $mainMenu->id,
                'title' => 'Nosotros',
                'type' => 'page',
                'page_id' => $nosotrosPageId,
                'menu_order' => 3,
            ],
            [
                'menu_id' => $mainMenu->id,
                'title' => 'FAQ',
                'type' => 'page',
                'page_id' => $faqPageId,
                'menu_order' => 4,
            ],
            [
                'menu_id' => $mainMenu->id,
                'title' => 'Contactar',
                'type' => 'page',
                'page_id' => $contactarPageId,
                'menu_order' => 5,
            ],
        ];

        foreach ($mainItems as $itemData) {
            // Build unique index for firstOrCreate
            $uniqueIndex = ['menu_id' => $itemData['menu_id'], 'title' => $itemData['title']];
            if (isset($itemData['page_id'])) {
                $uniqueIndex['page_id'] = $itemData['page_id'];
            }
            if (isset($itemData['url'])) {
                $uniqueIndex['url'] = $itemData['url'];
            }

            MenuItem::firstOrCreate(
                $uniqueIndex,
                $itemData
            );
        }

        // ============================================================
        // MENU ITEMS - Footer
        // ============================================================
        $avisoLegalPageId = Page::where('slug', 'aviso-legal')->first()->id;
        $privacidadPageId = Page::where('slug', 'privacidad')->first()->id;
        $cookiesPageId = Page::where('slug', 'cookies')->first()->id;

        $footerItems = [
            ['menu_id' => $footerMenu->id, 'title' => 'Inicio', 'type' => 'page', 'page_id' => $inicioPageId, 'menu_order' => 0],
            ['menu_id' => $footerMenu->id, 'title' => 'Vehículos', 'type' => 'page', 'page_id' => $vehiculosPageId, 'menu_order' => 1],
            ['menu_id' => $footerMenu->id, 'title' => 'Reservas', 'type' => 'page', 'page_id' => $reservasPageId, 'menu_order' => 2],
            ['menu_id' => $footerMenu->id, 'title' => 'Sobre Nosotros', 'type' => 'page', 'page_id' => $nosotrosPageId, 'menu_order' => 3],
            ['menu_id' => $footerMenu->id, 'title' => 'Preguntas Frecuentes', 'type' => 'page', 'page_id' => $faqPageId, 'menu_order' => 4],
            ['menu_id' => $footerMenu->id, 'title' => 'Contactar', 'type' => 'page', 'page_id' => $contactarPageId, 'menu_order' => 5],
            ['menu_id' => $footerMenu->id, 'title' => 'Aviso Legal', 'type' => 'page', 'page_id' => $avisoLegalPageId, 'menu_order' => 6],
            ['menu_id' => $footerMenu->id, 'title' => 'Política de Privacidad', 'type' => 'page', 'page_id' => $privacidadPageId, 'menu_order' => 7],
            ['menu_id' => $footerMenu->id, 'title' => 'Cookies', 'type' => 'page', 'page_id' => $cookiesPageId, 'menu_order' => 8],
        ];

        foreach ($footerItems as $itemData) {
            MenuItem::firstOrCreate(
                ['menu_id' => $itemData['menu_id'], 'title' => $itemData['title'], 'page_id' => $itemData['page_id']],
                $itemData
            );
        }
    }
}
