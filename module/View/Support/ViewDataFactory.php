<?php

namespace Module\View\Support;

use Closure;
use Exception;
use Takemo101\Egg\Support\Injector\ContainerContract;

/**
 * 共有データ
 */
final class ViewDataFactory
{
    /**
     * @var array<string,Closure>
     */
    private array $factories = [];

    /**
     * @var array<string,mixed>
     */
    private array $data = [];

    /**
     * constructor
     *
     * @param ContainerContract $container
     */
    public function __construct(
        private readonly ContainerContract $container,
    ) {
        //
    }

    /**
     * データの生成ハンドラを登録
     *
     * @param string $key
     * @param Closure $factory
     * @return self
     */
    public function addHandler(string $key, Closure $factory): self
    {
        $this->factories[$key] = $factory;

        return $this;
    }

    /**
     * データの生成ハンドラを取得
     *
     * @param string $key
     * @return Closure|null
     */
    private function handler(string $key): ?Closure
    {
        return $this->factories[$key] ?? null;
    }

    /**
     * データを取得する
     * データが存在しない場合は null を返す
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return $this->data[$key] = $this->call($key);
    }

    /**
     * ハンドラからデータを取得する
     * データが存在しない場合は null を返す
     *
     * @param string $key
     * @return mixed
     * @throws Exception
     */
    private function call(string $key): mixed
    {
        if ($handler = $this->handler($key)) {
            return $this->container->call($handler);
        }

        throw new Exception("{$key} is not found");
    }

    /**
     * マジックメソッドでデータを取得する
     * データが存在しない場合は null を返す
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key): mixed
    {
        return $this->get($key);
    }
}
