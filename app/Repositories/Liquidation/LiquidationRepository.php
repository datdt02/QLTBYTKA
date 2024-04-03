<?php

namespace App\Repositories\Liquidation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;

class LiquidationRepository implements LiquidationRepositoryInterface
{

    public function destroy($id)
   {
       return Liquidation::where('id',$id)->delete();
   }

   
}