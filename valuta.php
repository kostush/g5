<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Currency</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Currency Exchange Table</h2>
    <label for="currency">Choose a currency:</label>

    <select name="currency_selector" id="currency_selector">
        <option selected value="USD">USD</option>
        <option value="PLN">PLN</option>
        <option value="EUR">EUR</option>
        <option value="GBP">GBP</option>
    </select>


    <table id = "table" class="table">
    <thead class="thead-grey">
    <tr>

        <th scope="col">Currency Code</th>
        <th scope="col">Exchange yesterday</th>
        <th scope="col">Exchange Today</th>
    </tr>
    </thead>
    <tbody id="tableBody">

    </tbody>
</table>
</div>

<script>

    $( "#currency_selector" ).change(function() {
        //alert($('#currency_selector').val());
       var baseCurrency = $('#currency_selector').val();
       getExchangeStatus(baseCurrency);
    });

    function getCurrency(){

    }

    function getExchangeStatus(baseCurrency){
       // alert('post');
        $.post('https://skk-studio.com/5g/back.php',
            {'baseCurrency':baseCurrency},
            function(result){
                 answer = JSON.parse(result);
                 console.log(answer);
                if(answer.status =='error'){
                    alert(result);
                }else{
                    var arrayToRender=[];
                    console.log(answer.data);
                    for(rateDate in answer.data){
                       // console.log("Ключ: " + rateDate + " значение: " + answer.data[rateDate]['rates']);
                        for(key2 in answer.data[rateDate]['rates']){
                            //console.log("Ключ: " + key2 + " значение: " + answer.data[rateDate]['rates'][key2]);
                            render (rateDate,key2,answer.data[rateDate]['rates'][key2]);
                        }
                       /* dateCollumnToRender[]=key;
                        render_column(key,answer.data[key]['rates']);*/
                    }
                }
                //render(answer.data);
        });
    }

    function render(rateDate, valuta, rate){
        var tableResult,
            tableRow,
            rateYesterday;
        //console.log(rateDate+" "+ valuta+" " +rate);
       if($("#"+valuta).length){
           var index;
           index="#"+valuta+"_"+rateDate;
           rateYesyerday = ($(index).closest('tr:nth-child(2)').text());
           console.log( index+" "+rateYesterday);

           if ($("#"+valuta+"_"+rateDate).length){
               $("#"+valuta+"_"+rateDate).html(rate)

               console.log("значение ячейки " +rateYesterday);
              /* if(rateYesterday < rate($(index)).text()){
                   $(this).css("color","green");alert ("green");
               }else if(rateYesterday > rate(index).text()){
                   $(this).css("color","red");alert ("red");
               }*/

           }else{
               $("#"+valuta).append("<td id="+valuta+"_"+rateDate+" >"+rate+"</td>");
              04i
           }

       }else{
           tableRow = " <tr id="+valuta+">" +
               "<td id="+valuta+"_name >"+valuta+"</td>"+
               "<td id="+valuta+"_"+rateDate+" >"+rate+"</td>"+
               "</tr>";
           $("#tableBody").append(tableRow);

       }
    }

</script>
</body>