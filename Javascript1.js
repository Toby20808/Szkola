function parzyste(x, y){
    x = 7
    y = 2
    if (x % y == 0){
        console.log("liczba jest parzysta")
    } else {
        console.log("liczba nie jest parzysta")
    }
}
parzyste()

function matmadlt(a, b, c){
    a = prompt("podaj 1 liczbe delty")
    b = prompt("podaj 2 liczbe delty")
    c = prompt("podaj 3 liczbe delty")
    let dlt = (b* b) - (4 * a * c)
    return dlt
}
console.log(matmadlt())


let t1 = document.getElementById("t1")
let t2 = document.getElementById("t2")
function lancuchy(c, d){
    toString(c)
    toString(d)
    e = c + d
    return(e)
}

let btn1 = document.getElementById("btn1")
btn1.addEventListener("click", lancuchy(t1, t2))