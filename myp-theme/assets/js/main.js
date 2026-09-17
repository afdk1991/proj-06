/* MYP 全局交互 + 购物车 */
function mypApi(path, method, data) {
  return fetch(MYP_API.rest + path, {
    method: method || 'GET',
    headers: { 'X-WP-Nonce': MYP_API.nonce, 'Content-Type': 'application/json' },
    body: data ? JSON.stringify(data) : undefined
  }).then(r => r.json());
}

function mypAddCart(productId) {
  mypApi('cart', 'POST', { product_id: productId, quantity: 1 }).then(r => {
    alert(r.msg || '已加入购物车');
  });
}

function mypBuyNow(productId) {
  mypApi('cart', 'POST', { product_id: productId, quantity: 1 }).then(() => {
    location.href = MYP_API.rest.replace('/wp-json/', '/') + '../cart';
  });
}

jQuery(function ($) {
  // 购物车页
  if ($('#myp-cart-list').length) {
    mypApi('cart').then(r => {
      const items = r.data || [];
      if (!items.length) { $('#myp-cart-list').html('<p>购物车为空</p>'); return; }
      let html = '<table class="widefat"><tr><th>商品</th><th>单价</th><th>数量</th></tr>';
      items.forEach(it => {
        html += `<tr><td>${it.post_title}</td><td>¥${it.price}</td><td>${it.quantity}</td></tr>`;
      });
      html += '</table>';
      $('#myp-cart-list').html(html);
    });
  }
  // 用户中心
  if ($('#myp-user-orders').length) {
    mypApi('orders').then(r => {
      const list = r.data || [];
      $('#myp-user-orders').html(list.length ? '<pre>' + JSON.stringify(list, null, 2) + '</pre>' : '<p>暂无订单</p>');
    });
    mypApi('points').then(r => {
      $('#myp-user-points').html('当前积分：' + (r.data ? r.data.balance : 0));
    });
  }
});
