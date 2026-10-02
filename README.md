# DWES_Practica2
## Sistema Backend de Flota y Alquiler de Vehículos — EcoDrive

Módulo backend en PHP para la gestión interna de EcoDrive: validación de solicitudes de alquiler, procesamiento de reservas y generación de reportes de inventario de la flota.

## Estructura del proyecto


```
EcoDrive
├── procesador.php
├── reporte.php
└── README.md
```

## Descripción

El proyecto está dividido en dos scripts independientes, cada uno responsable de una parte del sistema:

procesador.php — Validación de la petición HTTP, cálculo del importe de una reserva y categorización por descuento/suplemento, con manejo de excepciones.

reporte.php — Gestión del catálogo de vehículos, tratamiento de texto multibyte (tildes y caracteres especiales), ordenación de la flota y generación de un reporte HTML con salida segura frente a XSS.
