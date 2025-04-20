<?php

namespace App\Http\Controllers\Domain;

use App\Http\Controllers\Controller;
use App\Http\Resources\DomainResource;
use App\Models\Domain;
use App\Services\DomainService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Random\RandomException;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DomainController extends Controller
{
    public function __construct(private readonly DomainService $domainService)
    {
        $this->authorizeResource(Domain::class);
    }

    /**
     * @return AnonymousResourceCollection<LengthAwarePaginator<DomainResource>>
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'filter' => ['nullable', 'array'],
            'filter.name' => ['nullable', 'string'],
            'page' => ['nullable', 'integer'],
            'sort' => ['nullable', 'string', Rule::in('name', '-name')],
        ]);

        $domains = QueryBuilder::for(Auth::user()->domains())
            ->allowedFilters([
                AllowedFilter::partial('name'),
            ])
            ->allowedSorts(['name'])
            ->paginate(50);

        return DomainResource::collection($domains);
    }

    /**
     * @throws RandomException
     */
    public function store(Request $request, DomainService $domainService): JsonResponse
    {
        /** @var array<string,string> $validated */
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'lowercase',
                'min:3',
                'max:255',
                Rule::unique(Domain::class),
                function (string $attribute, mixed $value, \Closure $fail) use ($domainService) {
                    if ($domainService->isInExcludedDomains($value)) {
                        $fail('You cannot use this domain name.');
                    }
                },
            ],
        ]);

        $domain = Auth::user()->domains()->create([
            ...$validated,
            'verification_code' => $this->domainService->generateVerificationCode(),
        ]);

        return new JsonResponse(new DomainResource($domain), 201);
    }

    public function destroy(Domain $domain): JsonResponse
    {
        $domain->delete();

        return new JsonResponse(['message' => 'Domain has been deleted successfully.']);
    }
}
