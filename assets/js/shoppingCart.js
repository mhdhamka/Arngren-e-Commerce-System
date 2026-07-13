
const selectAll = document.getElementById("selectAll");
const checkboxes = document.querySelectorAll(".product-check");
const priceTotal = document.getElementById("priceTotal");
const grandTotal = document.getElementById("grandTotal");

// SELECT ALL FUNCTION

selectAll.addEventListener("change", function(){


    checkboxes.forEach(function(box){

        box.checked = selectAll.checked;

    });


    calculateTotal();


});


// SINGLE CHECKBOX

checkboxes.forEach(function(box){


    box.addEventListener("change",function(){


        let allChecked = true;


        checkboxes.forEach(function(item){

            if(!item.checked)
            {
                allChecked=false;
            }

        });


        selectAll.checked = allChecked;


        calculateTotal();


    });


});


// CALCULATE TOTAL

function calculateTotal(){


    let total = 0;


    document.querySelectorAll(".product-check:checked")
    .forEach(function(box){


        let card = box.closest(".product-box");


        let price = parseFloat(
            card.dataset.price
        );


        let qty = parseInt(
            card.querySelector(".qty-input").value
        );


        total += price * qty;


    });

    priceTotal.innerHTML =
    "KR " + total.toFixed(2);



    grandTotal.innerHTML =
    "KR " + total.toFixed(2);



}


// QUANTITY CHANGE

document.querySelectorAll(".qty-input")
.forEach(function(input){


    input.addEventListener("change",function(){


        calculateTotal();


    });


});

// PLUS MINUS BUTTON

document.querySelectorAll(".qty-control button")
.forEach(function(btn){


    btn.addEventListener("click",function(e){


        let input =
        this.parentElement.querySelector(".qty-input");


        let value =
        parseInt(input.value);



        if(this.value=="plus")
        {
            value++;
        }


        else
        {
            if(value>1)
            {
                value--;
            }
        }


        input.value=value;


        calculateTotal();


    });



});

function deleteSelected()
{

    let checked = document.querySelectorAll('.product-check:checked');


    if(checked.length == 0)
    {
        alert("Please select product first");
        return;
    }


    let form = document.getElementById("deleteForm");


    checked.forEach(function(item){

        let input = document.createElement("input");

        input.type = "hidden";
        input.name = "cartID[]";
        input.value = item.value;

        form.appendChild(input);

    });


    form.submit();

}


// Select All

document.getElementById("selectAll").addEventListener("change",function(){

    let checkboxes = document.querySelectorAll(".product-check");


    checkboxes.forEach(function(box){

        box.checked = this.checked;

    },this);


});

function checkCart()
{

    let products = document.querySelectorAll(".product-check");


    if(products.length == 0)
    {
        alert("Your cart is empty!");
        window.location.href="../usershoppingCart.php";
        return false;
    }


    return true;

}

function sendCheckout()
{

    let selected = [];


    document.querySelectorAll(".product-check:checked")
    .forEach(function(item){

        selected.push(item.value);

    });


    if(selected.length == 0)
    {
        alert("Please select product first");
        return false;
    }


    document.getElementById("selectedCart").value = selected.join(",");


    return true;

}





