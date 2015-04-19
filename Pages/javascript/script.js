
    var date1,date2;
    var m_names = new Array("January", "February", "March", 
"April", "May", "June", "July", "August", "September", 
"October", "November", "December");
//function formats dates inside the bootstrap modal popups
$('#date1').change(function() {
    date1=$('#date1').val();
    date1_save = date1;
    var arr1 = date1.split('-');


var sup = "";
if (arr1[2] == 1 || arr1[2] == 21 || arr1[2] ==31)
   {
   sup = "st";
   }
else if (arr1[2] == 2 || arr1[2] == 22) 
   {
   sup = "nd";
   }
else if (arr1[2] == 3 || arr1[2] == 23)
   {
   sup = "rd";
   }
else
   {
   sup = "th";
   }

date1 = arr1[2] +  sup + " "
+ m_names[arr1[1]-1] + " " + arr1[0];
    $('#date1_modal').val(date1);
    console.log(date1);
    console.log(date1_save);
    $('#h_date1').val(date1_save);
});
$('#date2').change(function() {
    date2=$('#date2').val();
    date2_save = date2;
    var arr2 = date2.split('-');
var sup = "";
if (arr2[2] == 1 || arr2[2] == 21 || arr2[2] ==31)
   {
   sup = "st";
   }
else if (arr2[2] == 2 || arr2[2] == 22)
   {
   sup = "nd";
   }
else if (arr2[2] == 3 || arr2[2] == 23)
   {
   sup = "rd";
   }
else
   {
   sup = "th";
   }



date2 = arr2[2] + sup + " " + m_names[arr2[1]-1] + " " + arr2[0];

    $('#date2_modal').val(date2);
    console.log(date2_save);
    console.log(date2);
    $('#h_date2').val(date2_save);
});



//function autofils the values from the request form into the modal 
//takes the value in id = "text_a" and puts it in the place of id = "h_texta";
$('#text_a').change(function() {
    text_a=$('#text_a').val();
    console.log(text_a);
    $('#h_texta').val(text_a);
    });
    
    $('#unpaid').change(function() {
    unpaid=$('#unpaid').val();
    if ($('#unpaid').is(':checked'))
    {
        $( "#hours" ).prop( "disabled", true );
    }
    else
    {
         $( "#hours" ).prop( "disabled", false);
    }
    $('#h_unpaid').val(unpaid);
    });
    
    $('#hours').change(function() {
    hours=$('#hours').val();
    console.log(hours);
    $('#h_hours').val(hours);
    });
