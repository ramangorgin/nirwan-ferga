<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NormalizePersianDigits
{
    protected array $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹','٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
    protected array $english = ['0','1','2','3','4','5','6','7','8','9','0','1','2','3','4','5','6','7','8','9'];

    public function handle(Request $request, Closure $next)
    {
        $request->merge(
            $this->convertDigitsRecursive($request->all())
        );

        return $next($request);
    }

    private function convertDigitsRecursive(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->convertDigitsRecursive($value);
            } elseif (is_string($value)) {
                $data[$key] = str_replace($this->persian, $this->english, $value);
            }
        }

        return $data;
    }
}
