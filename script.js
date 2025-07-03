/* ============================= Typing animation =======================*/
var typed = new Typed(".typing",{
    strings:["Web Designer","Web Developer","Graphic Designer","Youtuber"],
    typeSpeed:100,
    Backspeed:60,
    loop:true
})
/* ================================ Aside ================================*/
const nav = document.querySelector(".nav"),
      navList = nav.querySelectorAll("li"),
      totalNavList = navList.length,
      allSection = document.querySelectorAll(".section"),
      totalSection = allSection.length;
      for(let i=0; i<totalNavList; i++)
      {
        const a = navList[i].querySelector("a");
        a.addEventListener("click", function()
        {
            for(let i=0 ; i<totalSection; i++)
            {
                allSection[i].classList.remove("back-section");
            }
            for(let j=0; j<totalNavList; j++)
            {
                if(navList[j].querySelector("a").classList.contains("active"))
                {
                    allSection[j].classList.add("back-section");
                }
                navList[j].querySelector("a").classList.remove("active");
            }
            this.classList.add("active")
            showSection(this);
        })   
      }
      function showSection(element) {
        for(let i=0 ; i<totalSection; i++) {
            allSection[i].classList.remove("active");
        }
        const target = element.getAttribute("href").split("#")[1];
        const targetSection = document.querySelector("#" + target);
        targetSection.classList.add("active");
        
        // 👇 Smooth scroll to section
        targetSection.scrollIntoView({ behavior: "smooth" });
    }



const navToggler = document.getElementById('navToggler');
  const aside = document.querySelector('.aside');

  navToggler.addEventListener('click', () => {
    navToggler.classList.toggle('open');   // 🔁 toggle hamburger animation
    aside.classList.toggle('open');        // 🔁 toggle sidebar
  });

  // Optional: Close on scroll (remove both open classes)
  window.addEventListener('scroll', () => {
    navToggler.classList.remove('open');
    aside.classList.remove('open');
  });