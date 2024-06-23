<?php

namespace App\Twig\Extension;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;


class PaginationExtension extends AbstractExtension
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly RequestStack $requestStack,
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_pagination', [$this, 'renderPagination'], ['is_safe' => ['html']])
        ];
    }


    public function renderPagination(int $currentPage = 0, int $pageCount = 0, string $filter = ''): string
    {
        $paginationHtml = '';
        $pageRange = 2;
        $lowerLimit = $currentPage - $pageRange;
        $upperLimit = $currentPage + $pageRange;

        if ($pageCount <= 1) {
            return $paginationHtml;
        }
        $queryParams = $this->requestStack->getMainRequest()->query;
        $queryParams->remove('page');
        $queryParams = $this->requestStack->getMainRequest()->query->all();

        $paginationHtml .= '<nav aria-label="Page navigation example">';
        $paginationHtml .= '<ul class="pagination justify-content-center">';

        // Previous Page
        if ($currentPage - 1 !== 0) {
            $url = $this->urlGenerator->generate(
                'app_client_case',
                array_merge(['page' => $currentPage - 1], $queryParams)
            );
            $previousPage = '<a class="page-link" href="'. $url .'" title="Previous">&laquo;</a>';

            $paginationHtml .= '<li class="page-item">';
            $paginationHtml .= $previousPage;
            $paginationHtml .= '</li>';
        }

        // First page and range
        if ($lowerLimit > 1) {
            $url = $this->urlGenerator->generate(
                'app_client_case',
                array_merge(['page' => 1], $queryParams)

            );

            $firstPage = '<a class="page-link" href="'. $url .'">1</a>';

            $paginationHtml .= '<li class="page-item">';
            $paginationHtml .= $firstPage;
            $paginationHtml .= '</li>';

            if ($lowerLimit > 2) {
                $paginationHtml .= '<li class="page-item disabled">';
                $paginationHtml .= '<a class="page-link" href="#" tabindex="-1" aria-disabled="true">...</a>';
                $paginationHtml .= '</li>';
            }
        }

        // Current page and page range
        for ($i = 1; $i <= $pageCount; $i++) {
            if ($i >= $lowerLimit && $i <= $upperLimit) {
                if ($currentPage === $i) {
                    $currentPage = '<a class="page-link">'. $currentPage .'</a>';

                    $paginationHtml .= '<li class="page-item active" aria-current="page">';
                    $paginationHtml .= $currentPage;
                } else {
                    $url = $this->urlGenerator->generate(
                        'app_client_case',
                        array_merge(['page' => $i], $queryParams)
                    );

                    $rangePage = '<a class="page-link" href="'. $url .'">'. $i .'</a>';

                    $paginationHtml .= '<li class="page-item">';
                    $paginationHtml .= $rangePage;
                }
                $paginationHtml .= '</li>';
            }
        }

        // Range and last page
        if ($upperLimit < $pageCount) {
            if ($upperLimit < $pageCount - 1) {
                $paginationHtml .= '<li class="page-item disabled">';
                $paginationHtml .= '<a class="page-link" href="#" tabindex="-1" aria-disabled="true">...</a>';
                $paginationHtml .= '</li>';
            }

            $url = $this->urlGenerator->generate(
                'app_client_case',
                array_merge(['page' => $pageCount], $queryParams)
            );

            $lastPage = '<a class="page-link" href="'. $url .'">'. $pageCount .'</a>';

            $paginationHtml .= '<li class="page-item">';
            $paginationHtml .= $lastPage;
            $paginationHtml .= '</li>';
        }


        // Next Page
        if ($currentPage < $pageCount) {
            $url = $this->urlGenerator->generate(
                'app_client_case',
                array_merge(['page' => $currentPage + 1], $queryParams)
            );

            $nextPage = '<a class="page-link" href="'. $url .'" title="Next">&raquo;</a>';

            $paginationHtml .= '<li class="page-item">';
            $paginationHtml .= $nextPage;
            $paginationHtml .= '</li>';
        }

        $paginationHtml .= '</ul>';
        $paginationHtml .= '</nav>';

        return $paginationHtml;
    }

}