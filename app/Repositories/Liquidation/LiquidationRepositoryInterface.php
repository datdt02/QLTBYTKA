<?php

namespace App\Repositories\Liquidation;
use Illuminate\Pagination\LengthAwarePaginator;

interface LiquidationRepositoryInterface
{
    public function destroy($id);
}