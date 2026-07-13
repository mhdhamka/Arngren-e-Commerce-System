

const monthLabels = <?php echo json_encode($months); ?>;
const monthSales = <?php echo json_encode($sales); ?>;

new Chart("salesChart",{

type:"line",

data:{
labels:monthLabels,

datasets:[{

label:"Sales (KR)",

data:monthSales,

fill:false,

borderColor:"#c45b56",

backgroundColor:"#c45b56",

borderWidth:3

}]
},

options:{
responsive:true,
legend:{display:true}
}

});



const productLabels = <?php echo json_encode($productNames); ?>;
const productQty = <?php echo json_encode($productQty); ?>;

new Chart("productChart",{

type:"bar",

data:{
labels:productLabels,

datasets:[{

label:"Quantity Sold",

data:productQty,

backgroundColor:[
"#c45b56",
"#e27d60",
"#f2a65a",
"#d95d39",
"#ef8354",
"#d7263d",
"#ffb703",
"#fb8500"
]

}]
},

options:{
responsive:true,
legend:{display:true}
}

});

