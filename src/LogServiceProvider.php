<?php

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 * Copyright (c) 2015 Yuuki Takezawa
 */

namespace Ytake\LaravelFluent;

use Illuminate\Support\ServiceProvider;

/**
 * Class LogServiceProvider
 *
 * @package Ytake\LaravelFluent
 */
class LogServiceProvider extends ServiceProvider
{
    /** @var bool */
    protected $defer = true;

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        /**
         * for package configure
         */
        $configPath = __DIR__ . '/config/fluent.php';
        $this->mergeConfigFrom($configPath, 'fluent');
        $this->publishes([
            $configPath => $this->resolveConfigurePath() . DIRECTORY_SEPARATOR . 'fluent.php'
        ], 'log');

        $this->app->bind('fluent.handler', function ($app) {
            return new RegisterPushHandler(
                $app['Psr\Log\LoggerInterface'], $app['config']->get('fluent')
            );
        });
    }

    /**
     * @return string
     */
    protected function resolveConfigurePath()
    {
        return (isset($this->app['path.config']))
            ? $this->app['path.config'] : $this->app->basePath() . DIRECTORY_SEPARATOR . 'config';
    }


    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return [
            'fluent.handler'
        ];
    }
}
