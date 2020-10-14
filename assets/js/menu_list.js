let test= document.querySelector('.menu').childNodes[1];
console.log(test);
let li = document.createElement('li');
let a = document.createElement('a');
li.className = "u-d-n-sp";
li.appendChild(a)
test.insertBefore(li,test.firstChild);
for (let index = 0; index < 5 ; index++) {
  test.insertBefore(li.cloneNode(true),test.firstChild);
}
console.log(test);