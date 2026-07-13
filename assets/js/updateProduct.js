const form = document.getElementById('form');
		const productName = document.getElementById('productName');
		const productQty = document.getElementById('productQty');
		const productPrice = document.getElementById('productPrice');

		form.addEventListener('submit', (e) => {
			e.preventDefault();
			validateInputs();
		});

		function sample() {
		  alert('Hello from formValidation.js!')
		}

		function validateInputs() {
			const productNameValue = productName.value.trim();
			const productQtyValue = productQty.value.trim();
			const productPriceValue = productPrice.value.trim();
	
			if (productNameValue === '' || productNameValue === null)
			{
				setInvalidFor(productName, 'Product Name cannot be blank.');
			}
			else
			{
				var checkName = /^([A-Z]){1}([a-z]){1,}$/;
				if (checkName.test(productNameValue) == false)
				{
					setInvalidFor(productName, 'First character must be uppercase, followed by lowercase.');
				}
				else
				{
					setValidFor(productName);
				}
			}
			
			if (productQtyValue === '')
			{
				setInvalidFor(productQty, 'Product Quantity cannot be blank.');
			}
			else
			{
				setValidFor(productQty);
			}
			
			if (productPriceValue === '')
			{
				setInvalidFor(productPrice, 'Product Price cannot be blank.');
			}
			else if (productPriceValue.length > 6)
			{
				var checkPrice = /(12345.67).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')/;
				if (checkPrice.test(productPriceValue) == false)
				{
					setInvalidFor(productPrice, 'Product price must be in correct format');
				}
				else
				{
					setValidFor(productPrice);
				}
			}
			else
			{
				setInvalidFor(productPrice, 'Product pricerice must be more than 6 digits.');
			}
		}

		function setInvalidFor(input, message) {
			const formControl = input.parentElement;
			const small = formControl.querySelector('small');
			
			small.innerText = message;
			
			formControl.className = 'form-control error';
			return false;
		}

		function setValidFor(input) {
			const formControl = input.parentElement;
			formControl.className = 'form-control success';
			return true;
		}