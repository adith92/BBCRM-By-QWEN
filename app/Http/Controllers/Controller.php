<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Backward-compat shim for legacy `$this->middleware(...)` calls inside
     * controller constructors.
     *
     * Laravel 11+ removed controller-level middleware resolution; middleware
     * is now declared on the routes themselves (see routes/web.php, where the
     * whole block is wrapped in `auth` and every route adds its own `role:`
     * middleware). This no-op keeps the legacy constructor calls from fatally
     * erroring while route-level middleware still enforces auth + roles.
     */
    public function middleware($middleware, array $options = [])
    {
        // Return a fluent no-op so legacy chains like
        // `$this->middleware('x')->only([...])` and `->except([...])` work.
        return new class {
            public function only($methods = null)
            {
                return $this;
            }

            public function except($methods = null)
            {
                return $this;
            }
        };
    }
}
