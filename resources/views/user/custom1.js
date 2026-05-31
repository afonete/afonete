
// Section 1 button click event
document.getElementById('payBtn').addEventListener('click', function() {
  // Hide section 1, show section 2
  document.getElementById('section1').classList.add('hidden');
  document.getElementById('section2').classList.remove('hidden');
});

// Get elements
const openPopupBtn = document.getElementById('openPopup');
const closePopupBtn = document.getElementById('closePopup');
const popupContainer = document.getElementById('popupContainer');

// Event listener to open popup
openPopupBtn.addEventListener('click', () => {
    const valueChoosed= document.getElementById('optionValue').value;
    const ammount= document.getElementById('ammount').value;
    // const userId= document.getElementById('id').innerHTML;
    const userId= 1;
    const perfectBlock= document.getElementById('perfectMoney');
    const advBlock= document.getElementById('advCash');
    const coinBlock= document.getElementById('bitCoin');
    const successBlock= document.getElementById('successBlock');
    successBlock.style.display="none";
    function showPerfectBlock()
    {
        perfectBlock.style.display="block";
        advBlock.style.display="none";
        coinBlock.style.display="none";
    }
    function showAdvBlock()
    {
        advBlock.style.display="block";
        perfectBlock.style.display="none";
        coinBlock.style.display="none";
    }
    function showCoinBlock()
    {
        coinBlock.style.display="block";
        perfectBlock.style.display="none";
        advBlock.style.display="none";
    }

if (valueChoosed !=='')
{
if (ammount !=='')
{

     if (valueChoosed == 'Advcash') {
        showAdvBlock();

    }
   else if (valueChoosed == 'Perfect money') {
        showPerfectBlock();

           }

            else {
        showCoinBlock();
         }

    popupContainer.classList.remove('hidden');
    document.getElementById('showValue').innerHTML=valueChoosed;
}
 else {
    alert('enter amount to deposit');
}
}
else{
    alert('choose payement option');
}
// Section 2 button click event
document.getElementById('confirmPayment').addEventListener('click', function() {
    const payementAccount=document.getElementById('paymentAccount').value;
    if (payementAccount ==='') {
        alert('enter payment account used to continue');
        return;
    }
// save transaction
successBlock.style.display="block";

});
});

// Event listener to close popup
closePopupBtn.addEventListener('click', () => {
  popupContainer.classList.add('hidden');

});

