<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/3/16 0016
 * Time: 下午 4:51
 */

class PageTool {

    /**
     * @param $url  分页时需要的连接:// /index.php?p=Admin&c=Goods&a=index
     * @param $count  总条数
     * @param $page   当前页码
     * @param $pageSize  页面多少条
     * @param array $params   分页时可以额外传递的参数
     * @return string    返回的分页工具条html代码
     */
    public static function show($url,$count,$page,$pageSize,$params=array()){
        $total_page = ceil($count/$pageSize); //总页数
        $pre_page = $page-1<1?1:$page-1; //上一页
        $next_page = $page+1>$total_page?$total_page:$page+1; //上一页

        //准备select中的option的html代码
        $option_html = '';
        for($i=1;$i<=$total_page;++$i){
            $option_html.="<option value='{$i}' ".($i==$page?'selected':'').">{$i}</option>";
        }

        //遇到的问题: 传入的url地址后面是否有参数   如果没有参数,index.php?    有参数的画, index.php?p=Admin&



        //将数组   $params = array('usrname'=>'zhangsan','age'=>28);  变成    name=value&name=value
        $query_str = http_build_query($params);

        $page_html=<<<PAGE_HTML
       <table id="page-table" cellspacing="0">
            <tbody><tr>
                <td align="right" nowrap="true">
                    <div id="turn-page">
                        总计  <span id="totalRecords">{$count}</span>
                        个记录分为 <span id="totalPages">{$total_page}</span>
                        页当前第 <span id="pageCurrent">{$page}</span>
                        页，每页 <input type="text" size="3" id="pageSize" value="{$pageSize}">
        <span id="page-link">
          <a href="javascript:goPage(1)">第一页</a>
          <a href="javascript:goPage({$pre_page})">上一页</a>
          <a href="javascript:goPage({$next_page})">下一页</a>
          <a href="javascript:goPage({$total_page})">最末页</a>
          <select id="gotoPage" onchange="goPage(this.value)">
                               {$option_html}
                        </select>
        </span>
                    </div>
                </td>
            </tr>
        </tbody></table>
        <script type="text/javascript">
            function goPage(page){
                //>>1.得到用户自定义的每页条数
                var pageSize = document.getElementById('pageSize').value;
                //>>2.发出请求时将上面的pageSize也传递给服务器
                window.location.href='{$url}&page='+page+'&pageSize='+pageSize+'&{$query_str}';
            }
        </script>
PAGE_HTML;

    return $page_html;
    }
}