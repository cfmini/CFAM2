<?php
/**
 *
 * @Author: 会飞的鱼
 * @Date: 2024/4/18 0018
 * @Time: 8:33
 * @Blog：https://houz.cn/
 * @Description: 会飞的鱼作品,禁止修改版权违者必究。
 */


namespace app\controller;
use think\Paginator;

class Page
{
    public function render()
    {
        if ($this->hasPages()) {
            if ($this->simple) {
                return sprintf(
                    '<div class="pagination">%s %s</div>',
                    $this->getPreviousButton(),
                    $this->getNextButton()
                );
            } else {
                $currentPage = $this->currentPage();
                $url = $this->url($currentPage);

                $pageLinks = [];
                $lastPage = $this->lastPage();

                // 上一页
                $prevPage = $currentPage - 1;
                $prevPageUrl = $prevPage >= 1 ? $this->url($prevPage) : 'javascript:void(0);';
                $pageLinks[] = '<li class="page-item disabled"><span class="page-link">Previous</span></li>';

                // 页码链接
                for ($i = 1; $i <= $lastPage; $i++) {
                    $class = ($currentPage == $i) ? 'active' : '';
                    $pageLinks[] = '<li class="page-item ' . $class . '"><a class="page-link" href="' . ($i == $currentPage ? '#' : $this->url($i)) . '">' . $i . '</a></li>';
                }

                // 下一页
                $nextPage = $currentPage + 1;
                $nextPageUrl = $nextPage <= $lastPage ? $this->url($nextPage) : 'javascript:void(0);';
                $pageLinks[] = '<li class="page-item"><a class="page-link" href="' . $nextPageUrl . '">Next</a></li>';

                return '<nav aria-label="...">  
                    <ul class="pagination">' . implode('', $pageLinks) . '</ul>  
                </nav>';
            }
        }

        return '';
    }
}