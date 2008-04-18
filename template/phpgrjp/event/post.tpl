{include file="header.tpl"}
<div>
<h2>Event Post</h2>
{if count($errors)}
 <ul>
  {foreach from=$errors item=error}
   <li>{$error}</li>
  {/foreach}
 </ul>
{/if}
{form method="post" class="actionform"}
{form_input name="id"}<br>
<dl>
<dt>{form_name name="name"}</dt><dd>{form_input name="name"}</dd>
<dt>{form_name name="publish_date"}</dt>
<dd>
{form_input name="publish_date"}
<input type="button" id="button_publish_date" value="timestamp" />
</dd>
<dt>{form_name name="start_date"}</dt>
<dd>
{form_input name="start_date"}
<input type="button" id="button_start_date" value="timestamp" />
</dd>
<dt>{form_name name="end_date"}</dt>
<dd>
{form_input name="end_date"}
<input type="button" id="button_end_date" value="timestamp" />
</dd>
<dt>{form_name name="due_date"}</dt>
<dd>
{form_input name="due_date"}
<input type="button" id="button_due_date" value="timestamp" />
</dd>
<dt>{form_name name="max_register"}</dt><dd>{form_input name="max_register"}</dd>
<dt>{form_name name="description"}</dt><dd>{form_input name="description" rows=9 cols=28}</dd>
<dt>{form_name name="private_description"}</dt><dd>{form_input name="private_description" rows=9 cols=28}</dd>
<dt>{form_name name="map"}</dt><dd>{form_input name="map"}</dd>
<dt>{form_name name="private"}</dt><dd>{form_input name="private" default=0}</dd>
</dl>
{form_input name="submit"}
{/form}
</div>

<div class="info">
<h4>Mapについて</h4>
<p>
Mapとは地図を貼り付ける機能の事でフォームの中にALPS Labsで提供されているALPSLAB Slideのパラメータ(ex.35/9/23,136/58/34)を
入力することで地図を表示させる事ができるようになります。
</p>
<p>
地図の座標は<a href="http://base.alpslab.jp/">ALPSLAB Base</a>から取得することができます。さらに具体的な説明は<a href="http://www.alpslab.jp/slide_howto.html">ALPSLAB Slide</a>を参照してください。
</p>
</div>
<script type="text/javascript" src="{$BASE_URL}/../js/lib/JSAN.js"></script>
<script type="text/javascript">
{literal}
JSAN.use('Widget.Dialog');
Widget.Dialog.prototype.timestamp = function(msg, options) {
    var opt = this._options;
    opt.height = 100;
    opt.width = 350;
    var options = this._extend(opt, options || {});

    this.addOverlay();

    var dwindow = this.getWindow(options.height, options.width);

    //FIXME
    var zeropadding = function (num, digit) {
      num = "" + num; //cast
      if (num.length < digit) {
        num = "0" + num;
      }
      return num;
    };

    // msg
    var dmsg = document.createElement('div');
    dmsg.id = 'dmsg';
    dmsg.style.padding = '6px';
    dmsg.appendChild(document.createTextNode(msg));
    dwindow.appendChild(dmsg);

    // selects
    var dselects = document.createElement('div');
    dselects.id = 'dselects';
    dselects.style.padding = '1px';

    // timestamp form
    var now_date = new Date();
    
    // timestamp::Year
    var dselect_year = document.createElement('select');
    dselect_year.className = 'dselect_year';

    var now_year = (now_date.getYear() < 2000) ? now_date.getYear() + 1900 : now_date.getYear(); 
    var range_year = 5;

    for (var year = now_year - range_year; year <= now_year + range_year; year++) {
        var option = document.createElement('option');
        option.value = year;
        option.innerHTML = year;
        if (now_year == year) {
          option.setAttribute('selected', 'selected');
        }
        dselect_year.appendChild(option);
    }
    dselects.appendChild(dselect_year);
    dselects.appendChild(document.createTextNode(" - "));

    //timestamp::Month
    var dselect_month = document.createElement('select');
    dselect_month.className = 'dselect_month';

    var now_month = now_date.getMonth() + 1;

    for (var month = 1; month <= 12; month++) {
        var option = document.createElement('option');
        option.value = zeropadding(month, 2);
        option.innerHTML = zeropadding(month, 2);
        if (now_month == month) {
          option.setAttribute('selected', 'selected');
        }
        dselect_month.appendChild(option);
    }
    dselects.appendChild(dselect_month);
    dselects.appendChild(document.createTextNode(" - "));

    //timestamp::Date(Day)
    var dselect_day = document.createElement('select');
    dselect_day.className = 'dselect_day';

    var now_day = now_date.getDate();

    for (var day = 1; day <= 31; day++) {
        var option = document.createElement('option');
        option.value = zeropadding(day, 2);
        option.innerHTML = zeropadding(day, 2);
        if (now_day == day) {
          option.setAttribute('selected', 'selected');
        }
        dselect_day.appendChild(option);
    }
    dselects.appendChild(dselect_day);
    dselects.appendChild(document.createTextNode(" "));

    //timestamp::Hour
    var dselect_hour = document.createElement('select');
    dselect_hour.className = 'dselect_hour';

    var now_hour = now_date.getHours();

    for (var hour = 0; hour <= 24; hour++) {
        var option = document.createElement('option');
        option.value = zeropadding(hour, 2);
        option.innerHTML = zeropadding(hour, 2);
        if (now_hour == hour) {
          option.setAttribute('selected', 'selected');
        }
        dselect_hour.appendChild(option);
    }
    dselects.appendChild(dselect_hour);
    dselects.appendChild(document.createTextNode(" : "));

    //timestamp::Minute
    var dselect_minute = document.createElement('select');
    dselect_minute.className = 'dselect_minute';

    var now_minute = now_date.getMinutes();

    for (var minute = 0; minute <= 59; minute++) {
        var option = document.createElement('option');
        option.value = zeropadding(minute, 2);
        option.innerHTML = zeropadding(minute, 2);
        if (now_minute == minute) {
          option.setAttribute('selected', 'selected');
        }
        dselect_minute.appendChild(option);
    }
    dselects.appendChild(dselect_minute);
    dselects.appendChild(document.createTextNode(" : "));

    //timestamp::Second
    var dselect_second = document.createElement('select');
    dselect_second.className = 'dselect_second';

    var now_second = now_date.getSeconds();

    for (var second = 0; second <= 59; second++) {
        var option = document.createElement('option');
        option.value = zeropadding(second, 2);
        option.innerHTML = zeropadding(second, 2);
        if (now_second == second) {
          option.setAttribute('selected', 'selected');
        }
        dselect_second.appendChild(option);
    }
    dselects.appendChild(dselect_second);

    // buttons
    var dbuttons = document.createElement('div');
    dbuttons.id = 'dbuttons';
    dbuttons.style.padding = '6px';

    // ok
    var dbuttonOk = document.createElement('button');
    dbuttonOk.className = 'dbutton';
    dbuttonOk.appendChild(document.createTextNode(options.labelOk));
    dbuttonOk.onclick = function() {
      options.onOk(""
        + dselect_year.value
        + "-"
        + dselect_month.value
        + "-"
        + dselect_day.value
        + " "
        + dselect_hour.value
        + ":"
        + dselect_minute.value
        + ":"
        + dselect_second.value
      );
    };
    dbuttons.appendChild(dbuttonOk);

    // cancel
    var dbuttonCancel = document.createElement('button');
    dbuttonCancel.className = 'dbutton';
    dbuttonCancel.appendChild(document.createTextNode(options.labelCancel));
    dbuttonCancel.onclick = options.onCancel;
    dbuttons.appendChild(dbuttonCancel);

    dwindow.appendChild(dselects);
    dwindow.appendChild(dbuttons);
    document.body.appendChild(dwindow);
    return this;
  };

  Widget.Dialog.timestamp = function (msg, options) {
    var dialog = new Widget.Dialog;
    return dialog.timestamp(msg, options);
  };
  
  //publish_date
  var button_publish_date = document.getElementById("button_publish_date");
  button_publish_date.onclick = function () {
    Widget.Dialog.timestamp("TIMESTAMP",{
        onOk: function (val) {
            document.forms[0].publish_date.value = val;
            Widget.Dialog.close();
        }
    });
  };
  
  //start_date
  var button_start_date = document.getElementById("button_start_date");
  button_start_date.onclick = function () {
    Widget.Dialog.timestamp("TIMESTAMP",{
        onOk: function (val) {
            document.forms[0].start_date.value = val;
            Widget.Dialog.close();
        }
    });
  };
  
  //end_date
  var button_end_date = document.getElementById("button_end_date");
  button_end_date.onclick = function () {
    Widget.Dialog.timestamp("TIMESTAMP",{
        onOk: function (val) {
            document.forms[0].end_date.value = val;
            Widget.Dialog.close();
        }
    });
  };
  
  //due_date
  var button_due_date = document.getElementById("button_due_date");
  button_due_date.onclick = function () {
    Widget.Dialog.timestamp("TIMESTAMP",{
        onOk: function (val) {
            document.forms[0].due_date.value = val;
            Widget.Dialog.close();
        }
    });
  };
{/literal}
</script>
{include file="footer.tpl"}
