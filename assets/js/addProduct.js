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
			const producQtyValue = productQty.value.trim();
			const productPriceValue = productPrice.value.trim();

	
			if (productNameValue === '' || productNameValue === null)
			{
				setInvalidFor(productName, 'Product name cannot be blank.');
			}
			else
			{
				setValidFor(productName);		
			}
			
			if (productQtyValue === '')
			{
				setInvalidFor(productQty, 'Quantity cannot be blank.');
			}
			else if (productQtyValue <= 0)
			{
				setInvalidFor(productQty, 'Quantity must be more than 0.');
			}
			else
			{
				setValidFor(productQty);
			}
			
			if (productPriceValue === '')
			{
				setInvalidFor(productPrice, 'Price cannot be blank.');
			}
			else if (productPriceValue.length <= 0)
			{
				setInvalidFor(productPrice, 'Price must be more than 0.');
				
			}
			else
			{
				setValidFor(productPrice);
			}
		}

		function setInvalidFor(input, message) {
			const formControl = input.parentElement;
			const small = formControl.querySelector('small');
			
			small.innerText = message;
			
			formControl.className = 'form-control error';
		}

		function setValidFor(input) {
			const formControl = input.parentElement;
			formControl.className = 'form-control success';
		}