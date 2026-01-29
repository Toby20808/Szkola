        document.getElementById("form1").addEventListener("submit", function(e){
            e.preventDefault()
            let x = Number(document.getElementById("x").value)
            let y = 2
            let wynik = document.getElementById("wynik1")
            if (x % y === 0) {
                wynik.textContent = "liczba jest parzysta"
            } else {
                wynik.textContent = "liczba jest nieparzysta"
            }
        })

        document.getElementById("form2").addEventListener("submit", function(e){
            e.preventDefault()
            let a = Number(document.getElementById("a").value)
            let b = Number(document.getElementById("b").value)
            let c = Number(document.getElementById("c").value)
            let dlt = (b*b) - (4*a*c)
            document.getElementById("wynik2").textContent = "Delta = " + dlt
        })

        document.getElementById("form3").addEventListener("submit", function(e){
            e.preventDefault()
            let t1 = document.getElementById("t1").value
            let t2 = document.getElementById("t2").value
            let polaczone = t1 + t2
            document.getElementById("wynik3").textContent = polaczone
        })