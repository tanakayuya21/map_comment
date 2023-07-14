<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
  <!-- External files -->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url');?>">
  <!-- Favicon, Thumbnail image -->
	<link rel="shortcut icon" href="<?php bloginfo('template_url');?>/images/favicon.ico">
	<script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js"></script>
  <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
  <title>MAP</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.0/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.3.0/dist/leaflet.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.0/dist/leaflet.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.3.0/dist/leaflet.js"></script>



  <?php if ( is_singular() ) wp_enqueue_script( "comment-reply" ); ?>
    <?php
      $result = "";
    if (isset($_POST['lat_input'])) {
      require_once(dirname(__FILE__) . "../../../../wp-load.php");
      $lat_input = isset($_POST['lat_input']) ? $_POST['lat_input'] : null;
      $lng_input = isset($_POST['lng_input']) ? $_POST['lng_input'] : null;
      $name_input = isset($_POST['name_input']) ? $_POST['name_input'] : null;
      $com_input = isset($_POST['com_input']) ? $_POST['com_input'] : null;

      //$result = //何らかの処理
      print json_encode($result);
      $wpdb->insert(
        "wp_points",
        array(
          'map_name' =>  $name_input,
          'map_lat' => $lat_input,
          'map_long' => $lng_input,
          'map_comment' => $com_input,
        ),
        array(
          '%s',
          '%s',
          '%s',
          '%s',
        )
      );
    $result = "登録しました";
  }
  echo $result;
?>
<?php
  global $sendVariableArray;
  //配列
  $sendVariableArray = [];
  global $wpdb;
  require_once(dirname(__FILE__) . "../../../../wp-load.php");
  $item = $wpdb->get_results("SELECT * FROM $wpdb->posts");
  echo esc_html(get_post_meta($post->ID, 'key', true));
  ?>
  <script>

  function init() {
    var map = L.map('map', {
    center: [35.685003743584275, 139.7521979511638],
    zoom: 13,
  });

  var tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="http://osm.org/copyright">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
  });

  tileLayer.addTo(map);
  const sendVariableArray = Array.from(<?php echo json_encode($item, JSON_UNESCAPED_UNICODE); ?>);
  map.on('click', onMapClick);
  var clickMarker = null;
  function onMapClick(e) {
    if (clickMarker) {
      map.removeLayer(clickMarker);
    }
    $('#name_input').val("");
    $('#com_input').val("");

    var lat = e.latlng.lat;
    var lng = e.latlng.lng;

    mapAdd(lat,lng);

    // 地理院地図サーバから標高を求める
    var src = 'https://cyberjapandata2.gsi.go.jp/general/dem/scripts/getelevation.php?lon=' + lng + '&lat=' + lat ;
    var req = new XMLHttpRequest();
    req.onreadystatechange = function() {
      if(req.readyState == 4 && req.status == 200) {
        var json = req.responseText;
        var results = JSON.parse(json);
        var popStr = '緯度：' + lat + '<br>経度：' + lng + '<br>標高：' + results.elevation + 'm';
        clickMarker = L.marker(e.latlng).on('click', onMarkerClick).addTo(map).bindPopup(popStr).openPopup();
      }
    };
    req.open("GET", src, false);
    req.send(null)
  }
  function onMarkerClick(e) {
    map.removeLayer(clickMarker);
  }

  var features = [];

  // GeoJSON形式で複数個のマーカーを設定する
  for (var i = 0; i < sendVariableArray.length; i++) {
    features.push({ 
      "type": "Feature",
      "properties": {
        "map_name": sendVariableArray[i].map_name
      },
      "geometry": {
        "type": "Point",
        "coordinates": [sendVariableArray[i].map_long, sendVariableArray[i].map_lat]
      },
      "url": sendVariableArray[i].guid
    });
  }

  map.on('singleclick',function ( e ) {
      L.popup().setLatLng( e.latlng )
      .setContent( '<p>singleclick</p>' + e.latlng )
      .openOn( map );
    } );
    L.geoJson(features, {
      onEachFeature: function(features, layer) {
        if (features.properties && features.properties.map_name) {
          layer.bindPopup(features.properties.map_name);
          // layer.bindPopup(features.properties.com);
          layer.on('mouseover', function(e) {
            this.openPopup();
          });
          layer.on('mouseout', function(e) {
            this.closePopup();
          });
          layer.on('tap', function(e) {		//スマホでマーカーをタップ
           	location.href = features.url;
          });
          layer.on('click', function(e) {
            // $('.sub').toggleClass('open');
            location.href = features.url;
          });
        }
      }
    }).addTo(map);
}

function outputPos(features) {
  //inputに出力
  $('#post_id').val(features.id);
  $('#lat_input').val(features.geometry.coordinates[0]);
  $('#lng_input').val(features.geometry.coordinates[1]);
  $('#name_input').val(features.properties.map_name);
  $('#com_input').val(features.map_com);
  $('#comment').val(features.map_com);
}

function mapAdd(lat, lng){
  //inputに出力
  $('#lat_input').val(lat);
  $('#lng_input').val(lng);
}

var _returnValues;
  function post() {
    var fd = new FormData();
    fd.append('lat_input', $('#lat_input').val());
    fd.append('lng_input', $('#lng_input').val());
    fd.append('name_input', $('#name_input').val());
    fd.append('com_input', $('#com_input').val());
    var xhr = new XMLHttpRequest();
    xhr.open('POST','index.php');
    xhr.send(fd);
    xhr.onreadystatechange = function(){
      if ((xhr.readyState == 4) && (xhr.status == 200)) {
        alert(xhr.responseText);
          _returnValues = JSON.parse(xhr.responseText);
      }
    };
  }
  
  $(function() {
      $('.js-btn').on('click', function() {
        // js-btnクラスをクリックすると、
        $('.sub , .btn-line , .menu-btn').toggleClass('open'); // メニューとバーガーの線にopenクラスをつけ外しする
      })
  });

</script>
</head>
<body class="main_body"  onload="init()">
<div class="main_form">
    <div class="sub">
      <div class="title_box">
        <div class="title"><?php bloginfo("name") ?></div>
        <button type="button" class="menu-btn js-btn">
          <span class="btn-line"></span>
        </button>
      </div>
      
    <ul>
      <?php 
        $args = array(
        'menu'            => '',
        'menu_class'      => 'menu', // メニューを構成するul要素につけるCSSクラス名
        'menu_id'         => '{メニューのスラッグ}-{連番}', // メニュを構成するul要素につけるCSSI ID名
        'container'       => 'div', // ulを囲う要素を指定。div or nav。なしの場合には false
        'container_class' => 'menu-{メニューのスラッグ}-container', // コンテナに適用するCSSクラス名
        'container_id'    => '', // コンテナに適用するCSS ID名
        'fallback_cb'     => 'wp_page_menu', // メニューが存在しない場合にコールバック関数を呼び出す
        'before'          => '', // メニューアイテムのリンクの前に挿入するテキスト
        'after'           => '', // メニューアイテムのリンクの後に挿入するテキスト
        'link_before'     => '', // リンク内の前に挿入するテキスト
        'link_after'      => '', // リンク内の後に挿入するテキスト
        'echo'            => true, // メニューをHTML出力する（true）かPHPの値で返す（false）か
        'depth'           => 0, // 何階層まで表示するか。0は全階層、1は親メニューまで、2は子メニューまで
        'walker'          => '', // カスタムウォーカーを使用する場合
        'theme_location'  => '', // メニュー位置を指定
        'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>', // メニューアイテムのラップの仕方。%1$sには'menu_id'のパラメータ展開、%2$sには'menu_class'のパラメータ展開、%3$sはリストの項目が値として展開
    );
    wp_nav_menu($args);
   ?>
    </ul>
  </div>
  <div class="map_form" id="map"> 
  </div>
</div>
</body>
</html>

