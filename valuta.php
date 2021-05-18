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
        <option value="EUR">EUR</option>
        <option value="PLN">PLN</option>
       

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
    $(document).ready(function(){
        getExchangeStatus($('#currency_selector').val());
    })

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
                    $("#tableBody").html('');
                }else{
                    var arrayToRender=[];
                    var yesterday = returnYYYYMMDD(-1);
                    console.log(answer.data);
                    console.log(yesterday);
                    for(rateDate in answer.data){
                       // console.log("Ключ: " + rateDate + " значение: " + answer.data[rateDate]['rates']);
                        for(key2 in answer.data[rateDate]['rates']){
                            //console.log("Ключ: " + key2 + " значение: " + answer.data[rateDate]['rates'][key2]);
                            render (rateDate,key2,answer.data[rateDate]['rates'][key2],yesterday);
                        }
                    }

                }
                //render(answer.data);
        });
    }

    function render(rateDate, valuta, rate,yesterday){
        var tableResult,
            tableRow;

        if(!$("#"+valuta).length){
           tableRow = " <tr id="+valuta+">" +
                      "<td id="+valuta+"_name >"+valuta+"</td>"+
                      "</tr>";
           $("#tableBody").append(tableRow);
        }
        if ($("#"+valuta+"_"+rateDate).length){
            $("#"+valuta+"_"+rateDate).html(rate)

        }else{
            $("#"+valuta).append("<td id="+valuta+"_"+rateDate+" >"+rate+"</td>");
            console.log($('#'+valuta+"_"+rateDate).text()+' '+ $('#'+valuta+"_"+yesterday).text());
            if(parseFloat($('#'+valuta+"_"+rateDate).text()) > parseFloat($('#'+valuta+"_"+yesterday).text())){
                $('#'+valuta+"_"+rateDate).css("color","green");
            }else if (parseFloat($('#'+valuta+"_"+rateDate).text()) < parseFloat($('#'+valuta+"_"+yesterday).text())){
                $('#'+valuta+"_"+rateDate).css("color","red");
            }

        }
    }
    function returnYYYYMMDD(numFromToday = 0){
        let d = new Date();
        d.setDate(d.getDate() + numFromToday);
        const month = d.getMonth() < 9 ? '0' + (d.getMonth() + 1) : d.getMonth() + 1;
        const day = d.getDate() < 10 ? '0' + d.getDate() : d.getDate();
        return `${d.getFullYear()}-${month}-${day}`;
    }

</script>
</body>