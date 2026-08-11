<?php

$pagesDir = __DIR__ . '/app/Filament/Resources';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($pagesDir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $modified = false;

        // Add redirect to index on Save in Pages
        if (strpos($file->getPathname(), '/Pages/') !== false) {
            $isCreateOrEdit = strpos($file->getFilename(), 'Create') !== false || strpos($file->getFilename(), 'Edit') !== false;
            
            if ($isCreateOrEdit && strpos($content, 'getRedirectUrl') === false) {
                // Find the closing brace of the class
                $pos = strrpos($content, '}');
                if ($pos !== false) {
                    $method = "\n    protected function getRedirectUrl(): string\n    {\n        return \$this->getResource()::getUrl('index');\n    }\n";
                    $content = substr_replace($content, $method, $pos, 0);
                    $modified = true;
                }
            }
        }

        // Unify ActionGroup in Resources to inline actions
        if (strpos($file->getPathname(), 'Resource.php') !== false) {
            if (preg_match('/Tables\\\\Actions\\\\ActionGroup::make\(\[\s*(.*?)\s*\]\)/s', $content, $matches)) {
                $actionsContent = $matches[1];
                $content = str_replace($matches[0], $actionsContent, $content);
                $modified = true;
            }
            
            // Standardize English status tags to Spanish
            $replacements = [
                "'unpaid' => 'Pendiente'" => "'unpaid' => 'Pendiente'",
                "'paid' => 'Pagado'" => "'paid' => 'Pagado'",
                "'draft' => 'draft'" => "'draft' => 'Borrador'",
                "'sent' => 'sent'" => "'sent' => 'Enviado'",
                "'pending' => 'pending'" => "'pending' => 'Pendiente'",
                "'active' => 'active'" => "'active' => 'Activo'",
                "'completed' => 'completed'" => "'completed' => 'Completado'",
                "'cancelled' => 'cancelled'" => "'cancelled' => 'Cancelado'",
                "return \$state;" => "return match(\$state) { 'unpaid' => 'Pendiente', 'paid' => 'Pagado', 'draft' => 'Borrador', 'sent' => 'Enviado', 'pending' => 'Pendiente', 'active' => 'Activo', 'completed' => 'Completado', 'cancelled' => 'Cancelado', default => \$state };",
            ];
            
            // Fix status badges in InvoiceResource
            if (strpos($file->getFilename(), 'InvoiceResource.php') !== false) {
                $content = str_replace("'paid' => 'paid'", "'paid' => 'Pagado'", $content);
                $content = str_replace("'draft' => 'draft'", "'draft' => 'Borrador'", $content);
                $content = str_replace("'sent' => 'sent'", "'sent' => 'Enviado'", $content);
                $content = str_replace("'overdue' => 'overdue'", "'overdue' => 'Vencido'", $content);
                
                $content = str_replace(
                    "fn (string \$state): string => \$state",
                    "fn (string \$state): string => match (\$state) { 'paid' => 'Pagado', 'draft' => 'Borrador', 'sent' => 'Enviado', 'overdue' => 'Vencido', default => \$state }",
                    $content
                );
                $modified = true;
            }
            
            // Fix status badges in BookingResource
            if (strpos($file->getFilename(), 'BookingResource.php') !== false) {
                $content = str_replace("'pending' => 'pending'", "'pending' => 'Pendiente'", $content);
                $content = str_replace("'active' => 'active'", "'active' => 'Activa'", $content);
                $content = str_replace("'completed' => 'completed'", "'completed' => 'Completada'", $content);
                $content = str_replace("'cancelled' => 'cancelled'", "'cancelled' => 'Cancelada'", $content);
                
                $content = str_replace(
                    "fn (string \$state): string => \$state",
                    "fn (string \$state): string => match (\$state) { 'pending' => 'Pendiente', 'active' => 'Activa', 'completed' => 'Completada', 'cancelled' => 'Cancelada', 'paid' => 'Pagado', 'unpaid' => 'Pendiente', default => \$state }",
                    $content
                );
                $modified = true;
            }
        }

        if ($modified) {
            file_put_contents($file->getPathname(), $content);
            echo "Modified: " . $file->getPathname() . "\n";
        }
    }
}

echo "Done.\n";
