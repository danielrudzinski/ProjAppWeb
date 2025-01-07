function loadMenu() {
    const isInPhpFolder = window.location.pathname.includes("/html/");
    const pathPrefix = isInPhpFolder ? "../" : "";

    const menu = `
        <table class="menu">
            <tr>
                <td><a href="${pathPrefix}index.php?page=dom">Strona Główna</a></td>
                <td><a href="${pathPrefix}index.php?page=filmy">Filmy</a></td>
                <td><a href="${pathPrefix}index.php?page=CODA">CODA</a></td>
                <td><a href="${pathPrefix}index.php?page=greenbook">Greenbook</a></td>
                <td><a href="${pathPrefix}index.php?page=NOMADLAND">Nomadland</a></td>
                <td><a href="${pathPrefix}index.php?page=Oppenheimer">Oppenheimer</a></td>
                <td><a href="${pathPrefix}index.php?page=parasite">Parasite</a></td>
                <td><a href="${pathPrefix}index.php?page=kontakt">Kontakt</a></td>

            </tr>
        </table>`;

    document.getElementById('menu').innerHTML = menu;

    $('#menu td').each(function() {
        $(this).data('originalWidth', $(this).width());
    });

    $('#menu td').on({
        mouseover: function() {
            const originalWidth = $(this).data('originalWidth'); 
            $(this).stop().animate({
                width: originalWidth + 50 
            }, 800);
        },
        mouseout: function() {
            const originalWidth = $(this).data('originalWidth'); 
            $(this).stop().animate({
                width: originalWidth 
            }, 800);
        }
    });

    console.log("Current Path:", window.location.pathname);
    console.log("Path Prefix:", pathPrefix);
}