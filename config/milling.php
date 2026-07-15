<?php

return [
    // Palay to rice conversion rate used when milling completes.
    // Example: 0.65 means 65% of palay kilos become rice kilos.
    'conversion_rate' => env('MILLING_CONVERSION_RATE', 0.65),
];
