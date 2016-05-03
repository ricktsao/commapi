<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>富網通 EDOMA 社區API Url Structure</title>

    <style type="text/css">

    ::selection { background-color: #E13300; color: white; }
    ::-moz-selection { background-color: #E13300; color: white; }

    body {
        background-color: #FFF;
        margin: 40px;
        font-family: "微軟正黑體", Arial, sans-serif;
        color: #4F5155;
		font-size: 14px;
        word-wrap: break-word;
		
    }

    a {
        color: #003399;
        background-color: transparent;
        font-weight: normal;
    }

    h1 {
        color: #444;
        background-color: transparent;
        border-bottom: 1px solid #D0D0D0;
        font-size: 24px;
        font-weight: normal;
        margin: 0 0 14px 0;
        padding: 14px 15px 10px 15px;
    }

    code {
        font-family: Consolas, Monaco, Courier New, Courier, monospace;
        font-size: 16px;
        background-color: #f9f9f9;
        border: 1px solid #D0D0D0;
        color: #002166;
        display: block;
        margin: 14px 0 14px 0;
        padding: 12px 10px 12px 10px;
    }

    #body {
        margin: 0 15px 0 15px;
    }

    table{
        border: 0px solid #D0D0D0;
        width: 900px;
    }

    td, th {
        border: 0px solid #D0D0D0;
        padding: 6px;
		background-color: #f2f2f2;
		vertical-align : middle;text-align: center;
		/*height: 45px;*/
    }

    p.footer {
        text-align: right;
        font-size: 16px;
        border-top: 1px solid #D0D0D0;
        line-height: 32px;
        padding: 0 10px 0 10px;
        margin: 20px 0 0 0;
    }

    #container {
        margin: 10px;
        border: 1px solid #D0D0D0;
        box-shadow: 0 0 8px #D0D0D0;
    }
	.left {text-align: left; padding-left:60px;}
    </style>
</head>
<body>

<div id="container">
    <!-- <h1>API Url Structure</h1> -->
    <h1>API 預設網址  http://27.147.4.239/commapi/
	<br>	
	</h1>
	
    <div id="body">
<h5 style="color:red">comm_id 可使用 5tgb4rfv<br>
			app_id 可使用 1255567822,1234507844,1234567233		
	</h5>
<div class="section" id="defining-the-api-news">
<h3>個人通知訊息</h3>
	<table border="1" >
		<thead valign="bottom">
			<tr class="row-odd"><th class="head">Method</th>
			<th class="head">URL</th>
			<th class="head">Params</th>
			<th class="head">Action</th>
		</tr>
		</thead>
		<tbody valign="top">
		<tr class="row-even"><td>POST</td>
			<td>/message/index/</td>
			<td class="left">comm_id : 社區ID<br>app_id : App ID</td>
			<td>
				取得用戶訊息列表
			</td>
		</tr>	
		<tr class="row-even"><td>POST</td>
			<td>/message/index/</td>
			<td class="left">comm_id : 社區ID<br>app_id : App ID<br>sn : 訊息編號</td>
			<td>取得指定的一則用戶訊息</td>
		</tr>	
		
		</tbody>
	</table>
</div>
	
	
<div class="section" id="defining-the-api-user">
<h3>住戶資訊  (參照 community_cloud.sys_user 資料表)</h3>
<table border="1" class="docutils">
<thead valign="bottom">
<tr class="row-odd"><th class="head">Method</th>
<th class="head">URL</th>
<th class="head">Params</th>
<th class="head">Action</th>
</tr>
</thead>
<tbody valign="top">
<tr class="row-odd"><td>POST</td>
<td>/user/index/</td>
<td class="left">comm_id : 社區ID<br /> id : 住戶ID <br /> app_id : 住戶App ID</td>
<td>取得指定的住戶資訊<br /> (簡單個資與權限)</td>
</tr>
<tr class="row-even"><td>POST</td>
<td>/user/activate/</td>
<td class="left">comm_id : 社區ID<br /> id : 住戶ID <br /> app_id : 住戶App ID</td>
<td>住戶開通</td>
</tr>

</tbody>
</table>
</div>


<div class="section" id="defining-the-api-news">
<h3>社區公告</h3>
<table border="1" class="docutils">
<thead valign="bottom">
<tr class="row-odd"><th class="head">Method</th>
<th class="head">URL</th>
<th class="head">Params</th>
<th class="head">Action</th>
</tr>
</thead>
<tbody valign="top">
<tr class="row-even"><td>GET</td>
<td>/news/index/</td>
<td class="left">comm_id : 社區ID</td>
<td>取得所有社區公告列表</td>
</tr>
<tr class="row-odd"><td>GET</td>
<td>/news/index/</td>
<td class="left">comm_id : 社區ID<br /> sn : 社區公告編號</td>
<td>取得指定的一則社區公告</td>
</tr>
<tr class="row-even"><td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
</div>


<div class="section" id="defining-the-api-news">
<h3>管委公告</h3>
<table border="1" class="docutils">
<thead valign="bottom">
<tr class="row-odd"><th class="head">Method</th>
<th class="head">URL</th>
<th class="head">Params</th>
<th class="head">Action</th>
</tr>
</thead>
<tbody valign="top">
<tr class="row-even"><td>GET</td>
<td>/bulletin/index/</td>
<td class="left">comm_id : 社區ID</td>
<td>取得所有管委公告列表</td>
</tr>
<tr class="row-odd"><td>GET</td>
<td>/bulletin/index/</td>
<td class="left">comm_id : 社區ID<br /> sn : 管委公告編號</td>
<td>取得指定的一則管委公告</td>
</tr>
<tr class="row-even"><td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
</div>

<div class="section" id="defining-the-api-news">
<h3>日行一善</h3>
<table border="1" class="docutils">
<thead valign="bottom">
<tr class="row-odd"><th class="head">Method</th>
<th class="head">URL</th>
<th class="head">Params</th>
<th class="head">Action</th>
</tr>
</thead>
<tbody valign="top">
<tr class="row-even"><td>GET</td>
<td>/daily_good/index/</td>
<td class="left">comm_id : 社區ID</td>
<td>取得所有日行一善列表</td>
</tr>
<tr class="row-odd"><td>GET</td>
<td>/daily_good/index/</td>
<td class="left">comm_id : 社區ID<br /> sn : 日行一善編號</td>
<td>取得指定的一則日行一善</td>
</tr>
<tr class="row-even"><td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
</div>

<div class="section" id="defining-the-api-news">
<h3>課程專區</h3>
<table border="1" class="docutils">
<thead valign="bottom">
<tr class="row-odd"><th class="head">Method</th>
<th class="head">URL</th>
<th class="head">Params</th>
<th class="head">Action</th>
</tr>
</thead>
<tbody valign="top">
<tr class="row-even"><td>GET</td>
<td>/course/index/</td>
<td class="left">comm_id : 社區ID</td>
<td>取得所有課程專區列表</td>
</tr>
<tr class="row-odd"><td>GET</td>
<td>/course/index/</td>
<td class="left">comm_id : 社區ID<br /> sn : 課程專區編號</td>
<td>取得指定的一則課程專區</td>
</tr>
<tr class="row-even"><td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
</div>

<div class="section" id="defining-the-api-news">
<h3>社區報修  </h3>
	<table border="1" >
		<thead valign="bottom">
			<tr class="row-odd"><th class="head">Method</th>
			<th class="head">URL</th>
			<th class="head">Params</th>
			<th class="head">Action</th>
		</tr>
		</thead>
		<tbody valign="top">
		<tr class="row-even"><td>POST</td>
			<td>/repair/event</td>
			<td class="left">
				comm_id : 社區ID<br>
				app_id : App ID<br>				
				repair_type : 維修範圍 (1:公共區域,2:住家內部)<br>
				repair_content : 報修內容
			</td>
			<td>
				報修				
			</td>
		</tr>	
		<tr class="row-even"><td>POST</td>
			<td>/repair/index/</td>
			<td class="left">comm_id : 社區ID<br>app_id : App ID</td>
			<td>
				查詢紀錄
			</td>
		</tr>	
		<tr class="row-even"><td>POST</td>
			<td>/detail/index/</td>
			<td class="left">comm_id : 社區ID<br>app_id : App ID<br>sn : 報修編號</td>
			<td>
			取得指定的一則報修回覆紀錄<br>
			reply_list : 回覆紀錄列表
			</td>
		</tr>	
		
		</tbody>
	</table>
</div>


<div class="section" id="defining-the-api-news">
<h3>意見箱  </h3>
	<table border="1" >
		<thead valign="bottom">
			<tr class="row-odd"><th class="head">Method</th>
			<th class="head">URL</th>
			<th class="head">Params</th>
			<th class="head">Action</th>
		</tr>
		</thead>
		<tbody valign="top">
		<tr class="row-even"><td>POST</td>
			<td>/suggestion/event</td>
			<td class="left">
				comm_id : 社區ID<br>
				app_id : App ID<br>
				title : 意見主旨<br>
				content : 意見內容<br>
				to_role : 收件對象 (a:管委收,s:總幹事收)<br>
			</td>
			<td>
				意見箱				
			</td>
		</tr>	
		<tr class="row-even"><td>POST</td>
			<td>/suggestion/index/</td>
			<td class="left">comm_id : 社區ID<br>app_id : App ID</td>
			<td>
				查詢紀錄
			</td>
		</tr>	
		<tr class="row-even"><td>POST</td>
			<td>/suggestion/index/</td>
			<td class="left">comm_id : 社區ID<br>app_id : App ID<br>sn : 意見箱編號</td>
			<td>
			取得指定的一則意見箱紀錄
			</td>
		</tr>	
		
		</tbody>
	</table>
</div>


<div class="section" id="defining-the-api-news">
<h3>瓦斯表</h3>
	<table border="1" >
		<thead valign="bottom">
			<tr class="row-odd"><th class="head">Method</th>
			<th class="head">URL</th>
			<th class="head">Params</th>
			<th class="head">Action</th>
		</tr>
		</thead>
		<tbody valign="top">
		<tr class="row-even"><td>POST</td>
			<td>/gas/readgas</td>
			<td class="left">
				comm_id : 社區ID<br>
				app_id : App ID<br>
				year : 年<br>
				month : 月<br>
				degress : 度數<br>
			</td>
			<td>
				抄表作業(return 1: 成功,0:失敗)				
			</td>
		</tr>	
		<tr class="row-even"><td>POST</td>
			<td>/gas/index/</td>
			<td class="left">comm_id : 社區ID<br>app_id : App ID</td>
			<td>
				查詢紀錄
			</td>
		</tr>	
		<tr class="row-even"><td>GET</td>
			<td>/gas/vender/</td>
			<td class="left">comm_id : 社區ID</td>
			<td>
			瓦斯公司基本資料
			</td>
		</tr>	
		
		</tbody>
	</table>
</div>

<div class="section" id="defining-the-api-rents">
<h3>租屋資訊</h3>
<table border="1" class="docutils">
<thead valign="bottom">
<tr class="row-odd"><th class="head">Method</th>
<th class="head">URL</th>
<th class="head">Params</th>
<th class="head">Action</th>
</tr>
</thead>
<tbody valign="top">
<tr class="row-even"><td>GET</td>
<td>/rent/index/</td>
<td class="left">comm_id : 社區ID</td>
<td>取得所有租屋資訊列表</td>
</tr>
<tr class="row-odd"><td>GET</td>
<td>/rent/index/</td>
<td class="left">comm_id : 社區ID<br /> sn : 租屋資訊編號</td>
<td>取得指定的一則租屋資訊</td>
</tr>
<tr class="row-odd"><td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
</div>


    </div>

    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php //echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>

</body>
</html>
