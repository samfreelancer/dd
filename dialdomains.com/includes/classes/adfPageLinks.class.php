<?php
class adfPageLinks {
    public function build($path, $current_page, $total_pages, $query_string = '?') {
        if ($total_pages == 1) {
            return '';
        }

        # verify that the $current_page is not greater than $total_pages
        $current_page = ($current_page > $total_pages) ? $total_pages : $current_page;

        $link_html = '<div class="pagination">';

        # creates links for previous pages
        if ($current_page > 1) {
            # display link for previous page
            $previous_page = $current_page - 1;
            $link_html .=  "<a href=\"$path{$query_string}page=$previous_page\" class=\"nextprev\" title=\"Go to Page $previous_page\">&#171; Previous</a> ";

            # display links for the 5 previous pages
            $previous_pages = max($current_page - 5, 1);

            # display a link for the first page if it will not be displayed
            if ($current_page > 6) {
                $link_html .= "<a href=\"$path{$query_string}page=1\" title=\"Go to page 1\">1</a><span>&#8230;</span>";
            }

            for ($i = $previous_pages; $i < $current_page; $i++) {
                $link_html .= "<a href=\"$path{$query_string}page=$i\" title=\"Go to page $i\">$i</a> ";
            }
        }

        # placeholder for current page
        $link_html .= "<span class=\"current\">$current_page</span>";

        # create links for next pages
        for ($i = $current_page + 1; $i <= min($current_page + 5, $total_pages); $i++) {
            $link_html .= "<a href=\"$path{$query_string}page=$i\" title=\"Go to page $i\">$i</a> ";
        }

        # create a link for the last page if it has not been displayed
        if ($total_pages - ($current_page + 5) >= 1) {
            $link_html .= "<span>&#8230;</span>";
            $link_html .= "<a href=\"$path{$query_string}page=$total_pages\" title=\"Go to page $total_pages\">$total_pages</a>";
        }

        # create link for next page if we aren't on the last page
        if ($current_page < $total_pages) {
            $next_page = $current_page + 1;
            $link_html .= "<a href=\"$path{$query_string}page=$next_page\" class=\"nextprev\" title=\"Go to Page $next_page\">Next &#187;</a>";
        }

        $link_html .= '</div>';

        return $link_html;
    }
}