<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use SystemHelper;

/**
 * App\Models\Config
 *
 * @property int         $id
 * @property string      $key
 * @property string|null $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Config newModelQuery()
 * @method static Builder|Config newQuery()
 * @method static Builder|Config query()
 * @method static Builder|Config whereCreatedAt($value)
 * @method static Builder|Config whereId($value)
 * @method static Builder|Config whereKey($value)
 * @method static Builder|Config whereUpdatedAt($value)
 * @method static Builder|Config whereValueEn($value)
 * @method static Builder|Config whereValueVi($value)
 * @mixin Eloquent
 */
class Config extends Model
{
    use HasFactory;

    protected $guarded
        = [
            'id',
            'created_at',
            'updated_at'
        ];

}
