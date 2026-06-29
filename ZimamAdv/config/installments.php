<?php

return [
    'min_amount' => (int) env('INSTALLMENT_MIN_AMOUNT', 1000000),
    'dp_percent' => (int) env('INSTALLMENT_DP_PERCENT', 50),
];
