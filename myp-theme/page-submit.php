<?php
/* Template Name: 发布产品页面 */
get_header(); ?>
<div class="container">
	<h1 style="padding:24px 0">发布产品</h1>
	<form method="post" style="background:#fff;padding:20px;border-radius:8px;max-width:600px">
		<p>标题 <input name="post_title" class="regular-text" style="width:100%"></p>
		<p>价格 <input name="myp_price" type="number" step="0.01" style="width:100%"></p>
		<p>库存 <input name="myp_stock" type="number" style="width:100%"></p>
		<p>详情 <textarea name="post_content" style="width:100%;height:120px"></textarea></p>
		<button class="btn btn-primary">提交</button>
	</form>
</div>
<?php get_footer(); ?>
