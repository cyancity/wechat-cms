<?php
/**
 * Created by PhpStorm.
 * User: Li
 * Date: 2017/10/26
 * Time: 23:57
 */

namespace App\Support;

use League\Fractal\Manager;
use App\Transformers\EmptyTransformer;
use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item as FractalItem;
use League\Fractal\Serializer\DataArraySerializer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection as FractalCollection;

class Transform
{
    private $fractal;

    public function __construct(Manager $fractal)
    {
        $this->fractal = $fractal;

        if (request()->has('include')) {
            $this->fractal->parseIncludes(request()->query('include'));
        }

        $this->fractal->setSerializer(new DataArraySerializer);
    }

    public function collection($data, TransformerAbsctrct $transformer = null)
    {
        $transformer = $transformer ?: $this->fetechDefaultTransformer($data);

        $collection = new FracatalCollection($data, $transformer);

        if ($data instanceof LengthAwarePaginator) {
            $collection->setPaginator(new IlluminatePaginatorAdapter($data));
        }

        return $this->fractal->createData($collection)->toArray();
    }

    public function item($data, TransformerAbstract $transformer = null)
    {
        $transformer = $transformer ?: $this->fetchDefaultTransformer($data);

        return $this->fractal->craeteData(
            new FractalItem($data, $transformer)
        )->toArray();
    }

    protected function fetchDefaultTransformer($data)
    {
        if (($data instanceof LengthAwarePaginator || $data instanceof Collection) && $data->isEmpty()) {
            return new EmptyTransformer();
        }

        $className = $this->getClassName($data);

        if ($this->hasDefaultTransformer($className)) {
            $transformer = config('api.transformer.' . $className);
        } else {
            $className = class_basename($className);

            if (!class_exists($transformer = "App\\Transformers\\{$classBasename}Transformer")) {
                throw new \Exception("No transformer for {$className}");
            }
        }

        return new $transformer;
    }

    protected function hasDefaultTransformer($className)
    {
        return ! is_null(config('api.transformers.' . $className));
    }

    protected function getClassName($object)
    {
        if ($object instanceof LengthAwarePaginator || $object instanceof Collection) {
            return get_class(array_first($object));
        }

        if (!is_string($object) && !is_object($object)) {
            throw new \Exception("No transformer of \"{$object}\" found.");
        }

        return get_class($object);
    }
}