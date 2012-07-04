<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class class_Pagination
{
    private $iTotalPage = 0;
    private $iCurrent = 1;

    public function set($aVars){
        if(isset($aVars['page'])){
            $this->iCurrent = $aVars['page'];
        }
        else{
            $this->iCurrent = 1;
        }
    }

    private function totalpages($iRowsPerPage, $iDataCount){
        $this->itotalpage = ceil($iDataCount / $iRowsPerPage);
    }

    private function createpagination(){
        $sPageURL = $_SERVER['REQUEST_URI'];
        $sConnector = preg_match('/\?/', $sPageURL) ? '&' : '?';
        $sPages = '<div class="pagination pagination-centered" >';
        $sPages .= '<ul>';
        $sPages .= '<li class="pages"><a href="javascript: void(0);">Page '.$this->iCurrent.' of '.$this->itotalpage.'&#8201;</a></li>';
        $bef = $this->iCurrent - 2;
        $aft = $this->iCurrent + 2;

        if(1 < $bef){
                $sPaginateURL = preg_match('/page=.[0-9]*/', $sPageURL) ? preg_replace('/page=[1-9][^&]*/', 'page=1', $sPageURL) : $sPageURL.$sConnector.'page=1';
                $sPages .= '<a href="'.$sPaginateURL.'">1</a>';
                if(2 < $bef){
                        $sPages .= '<li class="spacer"><a href="#">...</a></li>';
                }				
        }

        for($iPage = 1; $iPage <= $this->itotalpage; $iPage++){
                if($iPage == $this->iCurrent){
                        $sPages .= '<li class="current active"><a href="'.$sPaginateURL.'">'.$iPage.'</a></li>';
                }
                else{
                        $sPaginateURL = preg_match('/page=.[0-9]*/', $sPageURL) ? preg_replace('/page=[1-9][^&]*/', 'page='.$iPage, $sPageURL) : $sPageURL.$sConnector.'page='.$iPage;
                        if($iPage >= $bef && $iPage <= $aft){
                                $sPages .= '<a href="'.$sPaginateURL.'">'.$iPage.'</a>';	
                        }
                        else{
                        	$sPages .= '<span style="display:none;"><a href="'.$sPaginateURL.'">'.$iPage.'</a></span>';
                        }
                }
        }
        $lim = $this->itotalpage - 1;
        if($this->itotalpage > $aft){
                if($lim > $aft){
                        $sPages .= '<li class="spacer"><a href="#">...</a></li>';
                }
                $sPaginateURL = preg_match('/page=.[0-9]*/', $sPageURL) ? preg_replace('/page=[1-9][^&]*/', 'page='.$this->itotalpage, $sPageURL) : $sPageURL.$sConnector.'page='.$this->itotalpage;
                $sPages .= '<a href="'.$sPaginateURL.'">'.$this->itotalpage.'</a>';	
        }
        $sPages .= '</ul>';
        $sPages .= '</div>';

        return $sPages;
    }

    public function paginate($iRowsPerPage, $iDataCount){
        class_Pagination::totalpages($iRowsPerPage, $iDataCount);
        $sPagination = class_Pagination::createpagination();

        return $sPagination;
    }
}

$paginates = new class_Pagination($_GET);