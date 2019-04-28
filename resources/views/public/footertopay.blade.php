<div class="gwcpiao">
    <table>
        <tr>
            <th width="10%"><a href="javascript:history.back(-1)"><span
                            class="glyphicon glyphicon-menu-left"></span></a></th>
            <td width="35%">总计：<strong class="orange">¥<b id="total">@if(empty($total_money))0 @else{{$total_money}}@endif</b></strong></td>
            @if(isset($addcart))
            <td width="15%">
                <a href="javascript:;" class="jiesuan" id="addcart">加入购物车</a>
            </td>
            @endif
            @if(isset($delcart))
            <td width="15%" align="center">
                <a href="javascript:;"  class="jiesuan" style="background-color:#bbbbbb" id="delcart">删除选中</a>
            </td>
            @endif
            <td width="40%"><a href="javascript:;" class="jiesuan" id="close">去结算</a></td>
        </tr>
    </table>
</div>