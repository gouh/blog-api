<?php

namespace Gouh\BlogApi\App\Traits;

trait PaginateTrait
{
    /**
     * Create a page based on a count of items, filters that must contain offset and limit
     * as well as the total number of filtered elements
     *
     * @param int $page
     * @param int $limit
     * @param int $totalItems Number total of items
     * @param int $totalFilteredItems Filtered items
     * @return array
     */
    public function paginate(int $page, int $limit, int $totalItems, int $totalFilteredItems): array
    {
        $totalPages = ceil($totalItems / $limit);

        $range = $totalPages < 4 ? $totalPages : 4;
        $minRange = $page - $range;
        $minRange = $minRange >= 1 ? $minRange : 1;
        $minRange = $page >= ($totalPages - 4) && $totalPages > 10 ? ($totalPages - 9) : $minRange;
        $maxRange = $page <= 5 ? 10 : (($page + $range) + 1);
        $maxRange = $maxRange <= $totalPages ? $maxRange : $totalPages;
        $paginationInRange = range($minRange, $maxRange);


        return [
            'pageCount' => $totalPages,
            'itemCountPerPage' => $limit,
            'itemInPage' => $totalFilteredItems,
            'pagesInRange' => $paginationInRange,
            'first' => 1,
            'current' => $page,
            'last' => $totalPages,
            'next' => $page >= $totalPages ? $totalPages : ($page + 1),
            'totalItemCount' => $totalItems,
        ];
    }
}
