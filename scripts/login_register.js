$(document).ready(function(){
$('#register').click(function() {
         console.log('b');
         document.getElementById('logreg').classList.add('transform-active');
         document.getElementById('logreg').classList.remove('transform');
         document.getElementById('register').classList.add('select');
         document.getElementById('register').classList.remove('unselect');
         document.getElementById('login').classList.remove('select');
         document.getElementById('login').classList.add('unselect');
});
$('#login').click(function() {
         document.getElementById('logreg').classList.remove('transform-active');
         document.getElementById('logreg').classList.add('transform');
         document.getElementById('login').classList.add('select');
         document.getElementById('login').classList.remove('unselect');
         document.getElementById('register').classList.remove('select');
         document.getElementById('register').classList.add('unselect');
});
$('#c_submit').click(function() {
         document.getElementById('desc_box').classList.remove('no_submit');
         document.getElementById('desc_box').classList.add('submit');
});
});
