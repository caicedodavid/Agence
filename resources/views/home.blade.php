



<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Landing Page - Start Bootstrap Theme</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-transfer.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css">
    
    <!-- Custom styles for this template -->
    <link href="css/landing-page.min.css" rel="stylesheet">

  </head>

  <body>

    <!-- Navigation -->
    <nav class="navbar navbar-light bg-light static-top">
      <div class="container">
        <IMG alt="" src="inc/logo.gif" border=0>
      </div>
    </nav>

    <!-- Icons Grid -->
    <section class="features-icons bg-light text-center">
      <div class="container">
        <div class="input-append date" id="iniDate" data-date="2007-01" data-date-format="yyyy-mm" style="text-align: left!important; width:25%;">
          Desde:
            <input  type="text" id="inputini" readonly="readonly" class="form-control" name="date" placeholder="Desde">    
            <span class="add-on"><i class="icon-th"></i></span>      
        </div>
        <br>

        <div class="input-append date" id="endDate" data-date="2007-02" data-date-format="yyyy-mm" style="text-align: left!important; width:25%;">
        Hasta:
            <input  type="text" class="form-control" id="inputfin" readonly="readonly" name="date" placeholder="Hasta">    
            <span class="add-on"><i class="icon-th"></i></span>      
        </div>
        <br>
        <div class="row" style="margin-bottom: 20px">
            <div class="col-md-9">
              <div id="test" style="width:500px;margin: 0 auto;"></div>
            </div>
            <div class="col-md-3">
              <div class="btn-group-vertical pull-left">
                <button class="btn btn-primary" onclick="getRelatorio()">Relatorio</button>
                <button class="btn btn-primary">Gráfico</button>
                <button class="btn btn-primary">Pizza</button>
              </div>
            </div>
        </div>
        <div id="moneyTable" hidden>
        </div>
      </div>
    </section>



    <!-- Footer -->


    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="js/bootstrap-transfer.js"></script>
    <script type="text/javascript">


    $("#endDate").datepicker({
         format:'yyyy-mm',
         viewMode: "months",
         minViewMode: "months",
         startDate: '2007/03/03',
         startDate: new Date(2003, 00, 01),
         endDate: new Date(2007, 11, 31),
         autoclose: true
     })


    $("#iniDate").datepicker({
         format:'yyyy-mm',
         viewMode: "months",
         minViewMode: "months",
         startDate: new Date(2003, 00, 01),
         endDate: new Date(2007, 11, 31),
         autoclose: true
     }).on("changeDate", function(e) {
         value = $("#iniDate").datepicker("getDate");
         $("#endDate").datepicker("setStartDate", value)
         $("#endDate").prop('disabled', false);

     });
    $('#iniDate').datepicker('setDate', new Date(2007, 00, 01));
    $('#endDate').datepicker('setDate', new Date(2007, 11, 31));
    $("#endDate").prop('disabled', true);


        var t = $('#test').bootstrapTransfer();
        t.populate([
          @foreach ($users as $user)
            {value:"{{ $user->co_usuario}}", content:"{{ $user->no_usuario}}"},
          @endforeach
        ]);

    

    function getRelatorio () {
      $('#moneyTable').removeAttr('hidden');
      $.ajax({
          type: 'GET',
          url: '/getRelatorio',
          data: {info:JSON.stringify(t.get_values()), inic:$("#iniDate").val(), end: $("#endDate").val()},
          success: function(data) {
            updateTable(data); 
          },
          contentType: "application/json",
          dataType: 'json'
      });
    }

    function updateTable(data) {
      console.log(data);
      var months = {"01":"Enero", "02":"febrero", "03": "Marzo", "04":"Abril", "05":"Mayo", "06":"Junio", 
        "07":"Julio", "08":"Agosto", "09":"Septiembre", "10":"Octubre", "11":"Noviembre", "12":"Diciembre"};
      var userCode = data[0]["co_usuario"];
      var salary = data[0]["brut_salario"];
      var table = '<div id="moneyTable">';
      for (var i = 0; i < data.length; i++) {
        var user = data[i];
        table = table + '<div class="table-responsive"><table class="table"><thead><tr><th colspan="5">'+ user["no_usuario"] +'</th></tr></thead><tbody><tr><th>Período</th><th>Receita Líquida</th><th>Custo Fixo</th><th>Comissão</th><th>Lucro</th></tr>'      
        while ((data.length > i) && (user['co_usuario'] === userCode)) {
          var date = user['data_emissao'].slice(0, -3);
          table = table +'<tr><th>' + months[date.substr(5)] + '</th>';
          var gains = 0;
          var comision = 0;
          while ((data.length > i) && (user['data_emissao'].slice(0, -3) === date) ) {
            //console.log(user['co_usuario'], user['data_emissao'].slice(0, -3), date, user['valor'], user['total_imp_inc'], user['comissao_cn']);
            gains = gains + user['valor'];

            comision = comision + (user['valor'] - (user['valor'] * user['total_imp_inc']*0.01))*user['comissao_cn']*0.01;
            i++;
            user = data[i];
          }
          table = table +'<th>' + gains + '</th>';
          table = table +'<th>' + salary + '</th>';
          table = table +'<th>' + comision + '</th>';
          table = table +'<th>' + (gains - salary - comision) + '</th></tr>'; 
        }
        userCode = (i === data.length) ? "" : data[i]["co_usuario"];
        salary = (i === data.length) ? 0 : data[i]["brut_salario"];
        i--;
        table = table +'</tr></tbody></table></div>'
      }
      table = table + '</div>';
      $("#moneyTable").replaceWith(table);
    }

    </script>

  </body>

</html>