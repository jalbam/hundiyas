<?php
    //Se configura el BBClone:
    define("_BBC_PAGE_NAME", "spanish online");
    define("_BBCLONE_DIR", "../bbclone/");
    define("COUNTER", _BBCLONE_DIR."mark_page.php");
    if (is_readable(COUNTER)) include_once(COUNTER);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Hundiyas &copy; (por Joan Alba Maldonado)</title>
        <!-- (c) Hundiyas - Programa realizado por Joan Alba Maldonado (granvino@granvino.com). Prohibido publicar, reproducir o modificar sin citar expresamente al autor original. -->
        <script language="JavaScript1.2" type="text/javascript">
        <!--
                //(c) Hundiyas - Programa realizado por Joan Alba Maldonado (granvino@granvino.com). Prohibido publicar, reproducir o modificar sin citar expresamente al autor original.

                //Define el numero de casillas del tablero (de cada jugador):
                var tablero_ancho = 10; //Numero de casillas del tablero, en horizontal.
                var tablero_alto = 10; //Numero de casillas del tablero, en vertical.

                //Se aplican las restricciones para el menu de opciones:
                var tablero_ancho_maximo = 30; //Ancho maximo que puede tener el tablero.
                var tablero_alto_maximo = 30; //Alto maximo que puede tener el tablero.
                var tablero_ancho_minimo = 6; //Ancho minimo que puede tener el tablero.
                var tablero_alto_minimo = 5; //Alto minimo que puede tener el tablero.

                //Define el alto y ancho de cada celda (en numero de pixels):
                var celda_ancho_predeterminado = 40; //Numero por defecto de pixels del ancho de cada celda (el juego cambia estos valores automaticamente si tablero es muy grande).
                var celda_alto_predeterminado = 40; //Numero por defecto de pixels del alto de cada celda (el juego cambia estos valores automaticamente si tablero es muy grande).
                var celda_ancho = celda_ancho_predeterminado; //Numero de pixels del ancho de cada celda (varia segun alto y ancho del tablero).
                var celda_alto = celda_alto_predeterminado; //Numero de pixels del alto de cada celda (varia segun alto y ancho del tablero).

                //Define el espacio entre las celdas (vertical y horizontal):
                var espacio_entre_celdas = 2; //Numero de pixels de separacion entre una celda y otra (tanto en horizontal como en vertical).

                //Define si se adapta el alto y ancho de las celdas segun sea el tablero de grande:
                var adaptar_celdas = true;

                //Define la dificultad (0 = Facil, 1 = Normal, 2 = Dificil):
                var dificultad = 1;

                //Define si es posible bombardear una casilla ya bombardeada:
                var permitir_bombardear_lo_bombardeado = false;

                //Define si se permite o no poner dos barcos "juntos" (en casillas contiguas):
                var permitir_poner_barcos_adyacentes = false;
                
                //Define si se marca visualmente las casillas ya bombardeadas:
                var marcar_bombardeado = true;

                //Define quien comienza la partida:
                var primer_jugador = "ordenador";

                //Define el jugador actual:
                var jugador_actual = primer_jugador;

                //Define si el juego ya ha comenzado (para hacer un alert si quiere iniciarse otro juego nuevo, para no perder el actual):
                var se_ha_comenzado = false;

                //Para saber si se ha hecho click en un campo seleccionable y no arrastrar el menu de opciones si se mueve el raton:
                var campo_seleccionable = false;
                //Variables que calcularan la diferencia entre las coordenadas del mouse y las del div de opciones:
                var diferencia_posicion_horizontal = false;
                var diferencia_posicion_vertical = false;

                //Variables que calcularan la diferencia entre las coordenadas del mouse y las del div de las alertas:
                var diferencia_posicion_horizontal_alerta = false;
                var diferencia_posicion_vertical_alerta = false;

                //Matriz con el tablero del usuario:
                var tablero_usuario = new Array(tablero_ancho*tablero_alto);
                //Matriz con el tablero del ordenador:
                var tablero_ordenador = new Array(tablero_ancho*tablero_alto);

                //Guarda los barcos de cada usuario:
                var barcos = new Array(2);
                barcos["usuario"] = new Array(5); //Los 5 barcos del usuario.
                barcos["ordenador"] = new Array(5); //Los 5 barcos del ordenador.

                //Indica cual fue la celda a la que se le resaltaron sus coordenadas la ultima vez, para poder borrarlas cuando se seleccione otra celda:
                var ultima_celda_indicada = -1;

                //Matriz que contiene las ultimas celdas marcadas, para poder desmarcarlas luego (el indice 0 tiene -1 si no hay celdas marcadas anteriormente):
                var celdas_marcadas = new Array(tablero_ancho*tablero_alto);
                celdas_marcadas[0] = -1;

                //Define el barco que se esta arrastrando (0 = ninguno):
                var barco_arrastrandose = 0;

                //Matriz que contiene los barcos ya puestos:
                var barcos_puestos = new Array(5);
                //Matriz que contiene la primera celda donde se encuentran los barcos ya puestos:
                var barcos_puestos_primera_celda = new Array(5);

                //Define si se esta arrastrando el menu de opciones o no:
                var arrastrando_opciones = false;

                //Define si se esta arrastrando las alertas o no:
                var arrastrando_alerta = false;
                
                //Define si la pagina ya se ha cargado o no:
                var pagina_cargada = false;

                //Variables para la inteligencia artificial del ordenador:
                var posicion_barco = new Array(5); //Define la posicion de cada barco ("?", "vertical" u "horizontal").
                var cruz_barco = new Array(5);
                for (var x = 0; x < 5; x++) { cruz_barco[x] = new Array(2); cruz_barco[x]["vertical"] = new Array(2); cruz_barco[x]["horizontal"] = new Array(2); } //Guarda las celdas adyacentes en las que puede estar el mismo barco.
                
                //var sabe_que_barco_buscar = false; //Define si el ordenador sabe que ordenador ir a buscar o no.
                //var barco_buscado = -1; //Define el barco que busca (si busca alguno).
                var ultima_celda_bombardeada = -1; //Define la ultima celda bombardeada por el ordenador.
                //var ultimo_barco_alcanzado = -1; //Define el ultimo barco que ha bombardeado.
                var tablero_especulacion = new Array(tablero_ancho*tablero_alto); //El tablero que el ordenador va "especulando" o "descubriendo".
                var ultima_celda_conocida_barco = new Array(5); //Define la ultima celda conocida de cada barco.
                
                //Define si en las celdas se mostraran numeros o no:
                var mostrar_numero_celdas = false;
                
                //Define si las celdas donde el ordenador pone sus barcos se veran resaltadas al principio o no:
                var ver_barcos_ordenador = false;

                //Se define la variable que guardara el primer evento que se ejecute (para que no se ejecute onKeyDown y onKeyPress a la vez en Firefox, ya que podria causar dos rotaciones):
                var primer_evento = "";

                //Define si se activan o no los alert() y los confirm():
                var alerts_activados = false; //los alert() tendran substituto pero los confirm() no.

                //Define si el juego esta o no bloqueado:
                var juego_bloqueado = true;



                //Funcion que rota un barco al pulsar segun que tecla:
                function tecla_pulsada(e, evento_actual)
                {
                    //Si el juego esta bloqueado, sale de la funcion:
                    if (juego_bloqueado) { return; }

                    //Si el primer evento esta vacio, se le introduce como valor el evento actual (el que ha llamado a esta funcion):
                    if (primer_evento == "") { primer_evento = evento_actual; }
                    //Si el primer evento no es igual al evento actual (el que ha llamado a esta funcion), se vacia el primer evento (para que a la proxima llamada entre en la funcion) y se sale de la funcion:
                    if (primer_evento != evento_actual) { primer_evento = ""; return; }

                    //Capturamos la tacla pulsada (o liberada), segun navegador:
                    if (e.keyCode) { var unicode = e.keyCode; }
                    //else if (event.keyCode) { var unicode = event.keyCode; }
                    else if (window.Event && e.which) { var unicode = e.which; }
                    else { var unicode = 17; } //Si no existe, por defecto se utiliza el Control.

                    //Si la tecla pulsada no es ni Shift (16) ni Control (17) ni Alt (18) ni Intro (13) ni Backspace (8) ni Tabulador (9) sale de la funcion:
                    if (unicode != 16 && unicode != 17 && unicode != 18 && unicode != 13 && unicode != 8) { return; }
                    
                    //Rota el barco y retorna lo mismo que la funcion de rotar:
                    return rotar_barco();
                }


                //Funcion que muestra un mensaje:
                function mostrar_mensaje(mensaje)
                {
                    //Se pone el mensaje en el div correspondiente:
                    document.getElementById("mensaje").innerHTML = mensaje;
                    //Si el mensaje esta vacio, se deja de mostrar el div:
                    if (mensaje == "") { document.getElementById("reloj").style.visibility = "hidden"; document.getElementById("mensaje").style.visibility = "hidden"; }
                    else { document.getElementById("reloj").style.visibility = "visible"; document.getElementById("mensaje").style.visibility = "visible"; }
                }


                //Funcion que muestra una alerta (alternativa a alert()):
                function mostrar_alerta(mensaje, reiniciar_al_aceptar)
                {
                    //Se define si se reinicia o no al aceptar:
                    if (reiniciar_al_aceptar) { juego_bloqueado = true; }
                    else { juego_bloqueado = false; }
                    //Se pone el mensaje en el div correspondiente:
                    document.getElementById("alerta_mensaje").innerHTML = mensaje;
                    //Si el mensaje esta vacio, se deja de mostrar el div:
                    if (mensaje == "") { document.getElementById("alerta").style.visibility = "hidden"; document.getElementById("alerta_sombra").style.visibility = "hidden"; }
                    else { document.getElementById("alerta").style.visibility = "visible"; document.getElementById("alerta_sombra").style.visibility = "visible"; }
                    //Enfoca el boton (para que solo haga falta apretar intro o espacio para aceptar):
                    if (document.getElementById("alerta").style.visibility == "visible") { document.getElementById('formulario_alerta').boton_alerta.focus(); }
                }


                //Funcion que arrastra la ventana substituta de los alert():
                function arrastrar_alerta(e)
                {
                    //Si se ha parado de arrastrar, sale de la funcion:
                    if (!arrastrando_alerta) { diferencia_posicion_horizontal_alerta = false; diferencia_posicion_vertical_alerta = false; return; }
                    //...pero si se ha enviado arrastrar, se arrastra:
                    else
                    {
                        //Variable para saber si estamos en Internet Explorer o no:
                        var ie = document.all ? true : false;
                        //Si estamos en internet explorer, se recogen las coordenadas del raton de una forma:
                        if (ie)
                        {
                            posicion_x_raton = event.clientX + document.body.scrollLeft;
                            posicion_y_raton = event.clientY + document.body.scrollTop;
                        }
                        //...pero en otro navegador, se recogen de otra forma:
                        else
                        {
                            //document.captureEvents(Event.MOUSEMOVE);
                            posicion_x_raton = e.pageX;
                            posicion_y_raton = e.pageY;
                        } 
                        //Si las coordenadas X o Y del raton son menores que cero, se ponen a cero:
                        if (posicion_x_raton < 0) { posicion_x_raton = 0; }
                        if (posicion_y_raton < 0) { posicion_y_raton = 0; }

                        //Si se ha enviado arrastrar y no es un campo seleccionable, se arrastra:
                        //if (arrastrar_opciones && !campo_seleccionable)
                        if (!campo_seleccionable)
                        {
                            //Si es la primera vez que se arrastra despues del click, se calcula la diferencia inicial:
                            if (!diferencia_posicion_horizontal_alerta || !diferencia_posicion_vertical_alerta)
                            {
                                //Se calcula la diferencia que hay horizontalmente entre el raton y el div de los alerts:
                                diferencia_posicion_horizontal_alerta = eval(posicion_x_raton - parseInt(document.getElementById("alerta").style.left));
                                //Se calcula la diferencia que hay verticalmente entre el raton y el div de los alerts:
                                diferencia_posicion_vertical_alerta = eval(posicion_y_raton - parseInt(document.getElementById("alerta").style.top));
                            }
                            //Se calculan las nuevas coordenadas del div de los alerts:
                            var posicion_left_alerta = posicion_x_raton - diferencia_posicion_horizontal_alerta;
                            var posicion_top_alerta = posicion_y_raton - diferencia_posicion_vertical_alerta;
                            //Si alguna d las coordenadas fuera menos que cero, se ponen a cero:
                            if (posicion_left_alerta < 0) { posicion_left_alerta = 0; }
                            if (posicion_top_alerta < 0) { posicion_top_alerta = 0; }
                            //Se aplican las coordenadas al div de los alerts:
                            document.getElementById("alerta").style.left = posicion_left_alerta + "px";
                            document.getElementById("alerta").style.top = posicion_top_alerta + "px";
                            document.getElementById("alerta_sombra").style.left = posicion_left_alerta  + 4 + "px";
                            document.getElementById("alerta_sombra").style.top = posicion_top_alerta + 4 + "px";
                        }
                    }
                }

                
                //Funcion que arrastra el menu de opciones:
                function arrastrar_opciones(e)
                {
                    //Si se ha parado de arrastrar, sale de la funcion:
                    if (!arrastrando_opciones) { diferencia_posicion_horizontal = false; diferencia_posicion_vertical = false; return; }
                    //...pero si se ha enviado arrastrar, se arrastra:
                    else
                    {
                        //Variable para saber si estamos en Internet Explorer o no:
                        var ie = document.all ? true : false;
                        //Si estamos en internet explorer, se recogen las coordenadas del raton de una forma:
                        if (ie)
                        {
                            posicion_x_raton = event.clientX + document.body.scrollLeft;
                            posicion_y_raton = event.clientY + document.body.scrollTop;
                        }
                        //...pero en otro navegador, se recogen de otra forma:
                        else
                        {
                            //document.captureEvents(Event.MOUSEMOVE);
                            posicion_x_raton = e.pageX;
                            posicion_y_raton = e.pageY;
                        } 
                        //Si las coordenadas X o Y del raton son menores que cero, se ponen a cero:
                        if (posicion_x_raton < 0) { posicion_x_raton = 0; }
                        if (posicion_y_raton < 0) { posicion_y_raton = 0; }

                        //Si se ha enviado arrastrar y no es un campo seleccionable, se arrastra:
                        //if (arrastrar_opciones && !campo_seleccionable)
                        if (!campo_seleccionable)
                        {
                            //Si es la primera vez que se arrastra despues del click, se calcula la diferencia inicial:
                            if (!diferencia_posicion_horizontal || !diferencia_posicion_vertical)
                            {
                                //Se calcula la diferencia que hay horizontalmente entre el raton y el div de las opciones:
                                diferencia_posicion_horizontal = eval(posicion_x_raton - parseInt(document.getElementById("menu_opciones").style.left));
                                //Se calcula la diferencia que hay verticalmente entre el raton y el div de las opciones:
                                diferencia_posicion_vertical = eval(posicion_y_raton - parseInt(document.getElementById("menu_opciones").style.top));
                            }
                            //Se calculan las nuevas coordenadas del div de las opciones:
                            var posicion_left_menu = posicion_x_raton - diferencia_posicion_horizontal;
                            var posicion_top_menu = posicion_y_raton - diferencia_posicion_vertical;
                            //Si alguna d las coordenadas fuera menos que cero, se ponen a cero:
                            if (posicion_left_menu < 0) { posicion_left_menu = 0; }
                            if (posicion_top_menu < 0) { posicion_top_menu = 0; }
                            //Se aplican las coordenadas al div de las opciones:
                            document.getElementById("menu_opciones").style.left = posicion_left_menu + "px";
                            document.getElementById("menu_opciones").style.top = posicion_top_menu + "px";
                            document.getElementById("menu_opciones_sombra").style.left = posicion_left_menu  + 4 + "px";
                            document.getElementById("menu_opciones_sombra").style.top = posicion_top_menu + 4 + "px";
                        }
                    }
                }


                //Funcion que arrastra un barco (el div que lo contiene):
                function arrastrar_barco(e, numero)
                {
                    //Si el juego esta bloqueado, sale de la funcion:
                    //if (juego_bloqueado) { return; }

                    //Si se ha parado de arrastrar o el barco arrastrandose es 0 (ninguno), o esta el menu de opciones visible, sale de la funcion:
                    if (barco_arrastrandose == 0 || numero == 0 || document.getElementById("menu_opciones").style.visibility == "visible") { return; }
                    //...pero si se ha enviado arrastrar, se arrastra:
                    else
                    {
                        //Variable para saber si estamos en Internet Explorer o no:
                        var ie = document.all ? true : false;
                        //Si estamos en internet explorer, se recogen las coordenadas del raton de una forma:
                        if (ie)
                        {
                            posicion_x_raton = event.clientX + document.body.scrollLeft;
                            posicion_y_raton = event.clientY + document.body.scrollTop;
                        }
                        //...pero en otro navegador, se recogen de otra forma:
                        else
                        {
                            //document.captureEvents(Event.MOUSEMOVE);
                            posicion_x_raton = e.pageX;
                            posicion_y_raton = e.pageY;
                        } 
                        //Si las coordenadas X o Y del raton son menores que cero, se ponen a cero:
                        if (posicion_x_raton < 0) { posicion_x_raton = 0; }
                        if (posicion_y_raton < 0) { posicion_y_raton = 0; }

                        //Si se ha enviado arrastrar y no es un campo seleccionable, se arrastra:
                        //if (arrastrar_opciones && !campo_seleccionable)
                        if (!campo_seleccionable)
                        {
                            //Se calculan las nuevas coordenadas del div del barco:
                            //var posicion_left_barco = posicion_x_raton - parseInt(parseInt(document.getElementById("barco_" + numero).style.width)/2);
                            //var posicion_top_barco = posicion_y_raton - parseInt(parseInt(document.getElementById("barco_" + numero).style.height)/2);
                            var posicion_left_barco = posicion_x_raton - parseInt(celda_ancho / 2);
                            var posicion_top_barco = posicion_y_raton - parseInt(celda_alto / 2);
                            //Si alguna d las coordenadas fuera menos que cero, se ponen a cero:
                            if (posicion_left_barco < 0) { posicion_left_barco = 0; }
                            if (posicion_top_barco < 0) { posicion_top_barco = 0; }
                            //Se aplican las coordenadas al div del barco:
                            if (document.getElementById("barco_" + numero).style.visibility == "visible")
                            {
                                document.getElementById("barco_" + numero).style.left = posicion_left_barco + "px";
                                document.getElementById("barco_" + numero).style.top = posicion_top_barco + "px";
                            }
                            else if (document.getElementById("barco_" + numero + "_vertical").style.visibility == "visible")
                            {
                                document.getElementById("barco_" + numero + "_vertical").style.left = posicion_left_barco + "px";
                                document.getElementById("barco_" + numero + "_vertical").style.top = posicion_top_barco + "px";
                            }
                        }
                    }
                }


                //Funcion que arrastra la bomba:
                function arrastrar_bomba(e)
                {
                    //Si el juego esta bloqueado, sale de la funcion:
                    //if (juego_bloqueado) { return; }

                    //Si todavia no se ha comenzado, o no es el turno del usuario, o esta el menu de opciones visible, sale de la funcion:
                    //if (!se_ha_comenzado || jugador_actual != "usuario" || document.getElementById("menu_opciones").style.visibility == "visible") { return; }
                    //Siempre se arrastra la bomba aunque este invisible, menos cuando el menu de opciones esta visible que oculta la bomba y sale de la funcion:
                    if (document.getElementById("menu_opciones").style.visibility == "visible") { document.getElementById("bomba").style.visibility = "hidden"; return; }
                    //...pero si se ha enviado arrastrar, se arrastra:
                    else
                    {
                        //Variable para saber si estamos en Internet Explorer o no:
                        var ie = document.all ? true : false;
                        //Si estamos en internet explorer, se recogen las coordenadas del raton de una forma:
                        if (ie)
                        {
                            posicion_x_raton = event.clientX + document.body.scrollLeft;
                            posicion_y_raton = event.clientY + document.body.scrollTop;
                        }
                        //...pero en otro navegador, se recogen de otra forma:
                        else
                        {
                            //document.captureEvents(Event.MOUSEMOVE);
                            posicion_x_raton = e.pageX;
                            posicion_y_raton = e.pageY;
                        } 
                        //Si las coordenadas X o Y del raton son menores que cero, se ponen a cero:
                        if (posicion_x_raton < 0) { posicion_x_raton = 0; }
                        if (posicion_y_raton < 0) { posicion_y_raton = 0; }

                        //Si el cursor del raton esta mas arriba del tablero, y por lo tanto donde estan las opciones, se ocutla la bomba para que pueda hacerse click y sale de la funcion:
                        if (posicion_y_raton < parseInt(document.getElementById("zona_juego_usuario").style.top)) { document.getElementById("bomba").style.visibility = "hidden"; return; }
                        //...pero si no y el juego ha comenzado y le toca el usuario y el juego no esta bloqueado, la hace visible:
                        else if (se_ha_comenzado && jugador_actual == "usuario" && !juego_bloqueado) { document.getElementById("bomba").style.visibility = "visible"; }

                        //Si se ha enviado arrastrar y no es un campo seleccionable, se arrastra:
                        //if (arrastrar_opciones && !campo_seleccionable)
                        if (!campo_seleccionable) //??? (creo que no tiene sentido).
                        {
                            //Se calculan las nuevas coordenadas del div del barco:
                            //var posicion_left_barco = posicion_x_raton - parseInt(parseInt(document.getElementById("barco_" + numero).style.width)/2);
                            //var posicion_top_barco = posicion_y_raton - parseInt(parseInt(document.getElementById("barco_" + numero).style.height)/2);
                            var posicion_left_bomba = posicion_x_raton - parseInt(celda_ancho / 2);
                            var posicion_top_bomba = posicion_y_raton - parseInt(celda_alto / 2);
                            //Si alguna d las coordenadas fuera menos que cero, se ponen a cero:
                            if (posicion_left_bomba < 0) { posicion_left_bomba = 0; }
                            if (posicion_top_bomba < 0) { posicion_top_bomba = 0; }
                            //Se aplican las coordenadas al div del barco:
                            document.getElementById("bomba").style.left = posicion_left_bomba + "px";
                            document.getElementById("bomba").style.top = posicion_top_bomba + "px";
                        }
                    }
                }
                

                //Funcion que arrastra el reloj:
                function arrastrar_reloj(e)
                {
                    var ie = document.all ? true : false;
                    //Si estamos en internet explorer, se recogen las coordenadas del raton de una forma:
                    if (ie)
                    {
                        posicion_x_raton = event.clientX + document.body.scrollLeft;
                        posicion_y_raton = event.clientY + document.body.scrollTop;
                    }
                    //...pero en otro navegador, se recogen de otra forma:
                    else
                    {
                        //document.captureEvents(Event.MOUSEMOVE);
                        posicion_x_raton = e.pageX;
                        posicion_y_raton = e.pageY;
                    } 
                    //Si las coordenadas X o Y del raton son menores que cero, se ponen a cero:
                    if (posicion_x_raton < 0) { posicion_x_raton = 0; }
                    if (posicion_y_raton < 0) { posicion_y_raton = 0; }

                    if (!campo_seleccionable) //??? (creo que no tiene sentido).
                    {
                        var posicion_left_reloj = posicion_x_raton - parseInt(celda_ancho / 16);
                        //var posicion_left_reloj = posicion_x_raton - celda_ancho + 2;
                        var posicion_top_reloj = posicion_y_raton - parseInt(celda_alto / 16);
                        //var posicion_top_reloj = posicion_y_raton - celda_alto + 2;
                        //Si alguna d las coordenadas fuera menos que cero, se ponen a cero:
                        if (posicion_left_reloj < 0) { posicion_left_reloj = 0; }
                        if (posicion_top_reloj < 0) { posicion_top_reloj = 0; }
                        //Se aplican las coordenadas al div del barco:
                        document.getElementById("reloj").style.left = posicion_left_reloj + "px";
                        document.getElementById("reloj").style.top = posicion_top_reloj + "px";
                    }
                }


                //Funcion que recibe el barco escogido y comienza a arrastrarlo:
                function escoger_barco(numero)
                {
                    //Si el juego esta bloqueado, sale de la funcion:
                    if (juego_bloqueado) { return; }

                    //Si ha comenzado el juego o si el menu de opciones esta activo, sale de la funcion:
                    if (se_ha_comenzado || document.getElementById("menu_opciones").style.visibility == "visible") { return; }

                    //Si los demas barcos no han sido puestos en el tablero, vuelve a dejarlos en su sitio:
                    for (var x = 1; x <= 5; x++)
                    {
                        //Si los barcos no han sido puestos y no es el barco que se ha enviado escoger:
                        if (!barcos_puestos[x-1] && x != numero)
                        {

                            //Se posiciona horizontalmente:
                            if (x > 1) { document.getElementById("barco_" + x).style.left = parseInt(document.getElementById("barco_" + eval(x-1) + "_invisible").style.left) + parseInt(document.getElementById("barco_" + eval(x-1)).style.width) + espacio_entre_celdas + "px"; }
                            else { document.getElementById("barco_" + x).style.left = parseInt(document.getElementById("barcos").style.left) + espacio_entre_celdas + "px"; }
                            //Se posiciona verticalmente:
                            document.getElementById("barco_" + x).style.top = document.getElementById("barcos").style.top;
                            //Se pone en invisible el div transparente del barco:
                            document.getElementById("barco_" + x + "_invisible").style.visibility = "hidden";
                            //Se hace invisible el barco en vertical y visible el horizontal:
                            document.getElementById("barco_" + x + "_vertical").style.visibility = "hidden";
                            document.getElementById("barco_" + x).style.visibility = "visible";
                        }
                    }

                    //Si se ha seleccionado el barco numero 0, si esta arrastrando un barco lo deja, lo pone en horizontal, desaparece la opcion de rotar, y sale de la funcion:
                    if (numero == 0)
                    {
                        if (barco_arrastrandose != 0)
                        {
                            document.getElementById("barco_" + barco_arrastrandose + "_vertical").style.visibility = "hidden";
                            document.getElementById("barco_" + barco_arrastrandose).style.visibility = "visible";
                        }
                        //Hace invisibles todos los divs invisibles de los barcos puestos, para que no aparezca la mano del cusor sin tener un barco arrastrandose:
                        for (x = 1; x <= 5; x++)
                        {
                            if (barcos_puestos[x-1]) { document.getElementById("barco_" + x + "_invisible").style.visibility = "hidden"; }
                        }

                        barco_arrastrandose = 0;
                        document.getElementById("boton_rotar").style.visibility = "hidden";
                        return;
                    }
                    
                    //Da mas prioridad a los demas barcos, siempre que no esten puestos ya, para que puedan ser escogidos y hace invisible su div transparente:
                    for (x = 1; x <= 5; x++)
                    {
                        if (x != numero && !barcos_puestos[x-1])
                        {
                            document.getElementById("barco_" + x).style.zIndex = document.getElementById("barco_" + x + "_vertical").style.zIndex = 4;
                            document.getElementById("barco_" + x + "_invisible").style.visibility = "hidden";
                        }
                        else if (x == numero)
                        {
                            document.getElementById("barco_" + x).style.zIndex = document.getElementById("barco_" + x + "_vertical").style.zIndex = 2;
                        }
                        //Hace visible el div transparente donde estaba el barco escogido y todos los de los barcos ya puestos, para poder dejarlo si se prefiere:
                        if (x == numero || barcos_puestos[x-1])
                        {
                            document.getElementById("barco_" + x + "_invisible").style.visibility = "visible";
                            //Se quita el titulo que ponia "Dejar barco":
                            document.getElementById("barco_" + x + "_invisible").title = "Dejar barco";
                            document.getElementById("barco_" + x + "_img_invisible").title = "Dejar barco";
                        }
                    }

                    //Si estaba el barco ya puesto, lo quita  de los barcos ya puestos y de su matriz:
                    if (barcos_puestos[numero-1] && barcos_puestos_primera_celda[numero-1] >= 0)
                    {
                        //Define el numero de celda en el que el barco se encuentra:
                        var numero_celda = barcos_puestos_primera_celda[numero-1];
                        //Calcula si el barco esta en horizontal o en vertical:
                        var posicion_barco = (document.getElementById("barco_" + numero).style.visibility == "visible") ? "horizontal" : "vertical";
                        //Se define el incremento del bucle segun este el barco en vertical o en horizontal:
                        var incremento_bucle = (posicion_barco == "horizontal") ? 1 : tablero_ancho;
                        //Se define el tope del bucle segun este el barco en vertical o en horizontal:
                        var tope_bucle = (posicion_barco == "horizontal") ? numero_celda+numero : numero_celda+(numero*tablero_ancho);
                        //Si el barco se puede poner, marca la matriz como utilizada:
                        var numero_fila = parseInt(numero_celda/tablero_ancho);
                        var numero_columna = numero_celda - (numero_fila * tablero_ancho);
                        var numero_fila_actual = 0;
                        var numero_columna_actual = 0;
                        for (x = numero_celda; x < tope_bucle && x < tablero_ancho*tablero_alto; x += incremento_bucle)
                        {
                            //Calcula en que numero de fila se encuentra la celda enviada:
                            numero_fila_actual = parseInt(x/tablero_ancho);

                            //Calcula en que numero de columna se encuentra la celda enviada:
                            numero_columna_actual = x - (numero_fila_actual * tablero_ancho);

                            //Si el barco esta en horizontal y las columnas se acaban, sale del bucle:
                            if (posicion_barco == "horizontal" && numero_fila_actual > numero_fila) { break; }
                            //...pero si esta en vertical y las filas se acaban, sale del bucle tambien:
                            else if (posicion_barco == "vertical" && numero_columna_actual > numero_columna) { break; }
                                
                            //Borra el barco de la matriz:
                            tablero_usuario[x] = 0;
                        }

                        //Define como que ya no esta puesto:
                        barcos_puestos[numero-1] = false;
                        
                        //Borra de la matriz la primera celda donde estaba el barco puesto:
                        barcos_puestos_primera_celda[numero-1] = -1;
                    }

                    //Se hace visible la opcion de rotar:
                    document.getElementById("boton_rotar").style.visibility = "visible";

                    //Define como que se esta arrastrando el barco correspondiente:
                    barco_arrastrandose = numero;
                    
                    //Si todos los barcos estan puestos, aparece el boton de comenzar juego (pero si no, se oculta por si acaso):
                    var todos_puestos = true;
                    for (x = 0; x < 5; x++) { if (!barcos_puestos[x]) { todos_puestos = false; break; } }
                    if (todos_puestos) { document.getElementById("boton_comenzar_juego").style.visibility = "visible"; }
                    else { document.getElementById("boton_comenzar_juego").style.visibility = "hidden"; }
                }


                //Funcion que pone el barco en el tablero:
                function poner_barco(numero_celda)
                {
                    //Si el juego esta bloqueado, sale de la funcion:
                    if (juego_bloqueado) { return; }

                    //Se almacena el numero del barco que se esta arrastrando:
                    var numero = barco_arrastrandose; 

                    var posicion_barco;
                    
                    //Calcula si el barco esta en horizontal o en vertical:
                    if (numero != 0) { posicion_barco = (document.getElementById("barco_" + numero).style.visibility == "visible") ? "horizontal" : "vertical"; }

                    //Si se estaba arrastrando un barco:
                    if (numero != 0 && se_puede_poner_barco(numero_celda, numero, posicion_barco, "usuario"))
                    {
                        //Se define como que ya no se esta arrastrando ningun barco:
                        barco_arrastrandose = 0;
                        //Se define como que el barco se ha puesto en el tablero:
                        barcos_puestos[numero-1] = true;
                        //Se define la primera celda donde se encuentra el barco puesto:
                        barcos_puestos_primera_celda[numero-1] = numero_celda;
                        //Sube la prioridad del barco para que pueda volver a cogerse:
                        document.getElementById("barco_" + numero).style.zIndex = document.getElementById("barco_" + numero + "_vertical").style.zIndex = 5;
                        //Se posiciona horizontal y verticalmente el barco:
                        document.getElementById("barco_" + numero).style.left = document.getElementById("barco_" + numero + "_vertical").style.left = parseInt(document.getElementById("celda_" + numero_celda + "_usuario").style.left) + parseInt(document.getElementById("zona_juego_usuario").style.left) + "px";
                        document.getElementById("barco_" + numero).style.top = document.getElementById("barco_" + numero + "_vertical").style.top = parseInt(document.getElementById("celda_" + numero_celda + "_usuario").style.top) + parseInt(document.getElementById("zona_juego_usuario").style.top) + "px";
                        //Se ajusta mejor el barco, segun se haya puesto en vertical o en horizontal, ya que las celdas estan separadas por espacio:
                        if (document.getElementById("barco_" + numero).style.visibility == "visible")
                        {
                            document.getElementById("barco_" + numero).style.left = parseInt(document.getElementById("barco_" + numero).style.left) + parseInt(espacio_entre_celdas * (numero-1) / 2) + "px";
                        }
                        else
                        {
                            document.getElementById("barco_" + numero + "_vertical").style.top = parseInt(document.getElementById("barco_" + numero + "_vertical").style.top) + parseInt(espacio_entre_celdas * (numero-1) / 2) + "px";
                        }
                        //Se esconde la opcion de rotar:
                        document.getElementById("boton_rotar").style.visibility = "hidden";

                        //Hace invisibles todos los divs invisibles de los barcos puestos, para que no aparezca la mano del cusor sin tener un barco arrastrandose:
                        for (var x = 1; x <= 5; x++)
                        {
                            if (barcos_puestos[x-1]) { document.getElementById("barco_" + x + "_invisible").style.visibility = "hidden"; document.getElementById("barco_" + x + "_invisible").title = ""; document.getElementById("barco_" + x + "_img_invisible").title = ""; }
                        }

                        //Desmarca las celdas marcadas anteriormente, si la matriz no es -1:
                        if (celdas_marcadas[0] >= 0)
                        {
                            for (x = 0; x < celdas_marcadas.length; x++)
                            {
                                if (celdas_marcadas[x] < 0) { break; }
                                document.getElementById('celda_' + celdas_marcadas[x] + '_usuario').style.background = "#6600ff";
                                celdas_marcadas[x] = -1;
                            }
                        }

                        //Hace invisible el div transparente donde estaba el barco escogido, para que ya no salga el cursor en forma de mano al ponerse en el hueco (se volvera a hacer visible al volver a coger el barco, si se hace):
                        //document.getElementById("barco_" + numero + "_invisible").style.visibility = "hidden";
                        
                        //Calcula en que numero de fila se encuentra la celda enviada:
                        var numero_fila = parseInt(numero_celda/tablero_ancho);
                        //Calcula en que numero de columna se encuentra la celda enviada:
                        var numero_columna = numero_celda - (numero_fila * tablero_ancho);
                        //Se define el incremento del bucle segun este el barco en vertical o en horizontal:
                        var incremento_bucle = (posicion_barco == "horizontal") ? 1 : tablero_ancho;
                        //Se define el tope del bucle segun este el barco en vertical o en horizontal:
                        var tope_bucle = (posicion_barco == "horizontal") ? numero_celda+numero : numero_celda+(numero*tablero_ancho);
                        //Si el barco se puede poner, marca la matriz como utilizada:
                        var numero_fila_actual = 0;
                        var numero_columna_actual = 0;
                        for (x = numero_celda; x < tope_bucle && x < tablero_ancho*tablero_alto; x += incremento_bucle)
                        {
                            //Calcula en que numero de fila se encuentra la celda enviada:
                            numero_fila_actual = parseInt(x/tablero_ancho);

                            //Calcula en que numero de columna se encuentra la celda enviada:
                            numero_columna_actual = x - (numero_fila_actual * tablero_ancho);

                            //Si el barco esta en horizontal y las columnas se acaban, sale del bucle:
                            if (posicion_barco == "horizontal" && numero_fila_actual > numero_fila) { break; }
                            //...pero si esta en vertical y las filas se acaban, sale del bucle tambien:
                            else if (posicion_barco == "vertical" && numero_columna_actual > numero_columna) { break; }
                                
                            tablero_usuario[x] = numero;
                        }

                        //Si todos los barcos estan puestos, aparece el boton de comenzar juego (pero si no, se oculta por si acaso):
                        var todos_puestos = true;
                        for (x = 0; x < 5; x++) { if (!barcos_puestos[x]) { todos_puestos = false; break; } }
                        if (todos_puestos) { document.getElementById("boton_comenzar_juego").style.visibility = "visible"; }
                        else { document.getElementById("boton_comenzar_juego").style.visibility = "hidden"; }
                    }
                }


                //Funcion que define si un barco puede ponerse en el tablero especulado en la posicion marcada o no:
                function se_puede_poner_barco_especulando(numero_celda, numero_barco, posicion_barco, mirar_adyacentes)
                {
                    //Tener en cuenta el tama�o del barco arrastrandose, si esta en vertical o en horizontal y los barcos que crucen:
                    //Se define el incremento del bucle segun este el barco en vertical o en horizontal:
                    var incremento_bucle = (posicion_barco == "horizontal") ? 1 : tablero_ancho;
                    //Se define el tope del bucle segun este el barco en vertical o en horizontal:
                    var tope_bucle = (posicion_barco == "horizontal") ? numero_celda+numero_barco : numero_celda+(numero_barco*tablero_ancho);
                    //Si el barco no va a caber, sale de la funcion:
                    var numero_fila_actual = parseInt(numero_celda/tablero_ancho);
                    var numero_columna_actual = numero_celda - (numero_fila_actual * tablero_ancho);
                    var numero_fila = numero_fila_actual;
                    var numero_columna = numero_columna_actual;
                    //if (posicion_barco == "horizontal" && numero_columna + numero_barco > tablero_ancho) { return false; }
                    //else if (posicion_barco == "vertical" && numero_fila + numero_barco > tablero_alto) { return false; }
                    //Calcula si hay barcos que cruzan:
                    var celdas_a_rellenar = numero_barco + 1;
                    for (var x = numero_celda; x <= tope_bucle && x < tablero_ancho*tablero_alto; x += incremento_bucle)
                    {
                        //Calcula en que numero de fila se encuentra la celda enviada:
                        numero_fila_actual = parseInt(x/tablero_ancho);

                        //Calcula en que numero de columna se encuentra la celda enviada:
                        numero_columna_actual = x - (numero_fila_actual * tablero_ancho);

                        //Si el barco esta en horizontal y las columnas se acaban, sale del bucle:
                        if (posicion_barco == "horizontal" && numero_fila_actual > numero_fila) { break; }
                        //...pero si esta en vertical y las filas se acaban, sale del bucle tambien:
                        else if (posicion_barco == "vertical" && numero_columna_actual > numero_columna) { break; }

                        //Si la casilla ya esta ocupada y no es el barco que buscamos, sale del bucle:
                        if (tablero_especulacion[x] != -1 && tablero_especulacion[x] != numero_barco+1) { break; }

                        //Si esta desmarcada la casilla de poder poner barcos adyacentes, comprobar si hay barcos adyacentes y si los hay no se puede poner:
                        if (mirar_adyacentes && !permitir_poner_barcos_adyacentes && hay_barcos_adyacentes_especulando(x, numero_barco)) { break; }

                        celdas_a_rellenar--;
                    }
                    //Si no se ha podido poner todo el barco en el tablero, prueba a poner el resto del barco a la izquierda o arriba:
                    //var inicio_bucle = (posicion_barco == "horizontal") ? numero_celda : numero_celda;
                    //var inicio_bucle = numero_celda;
                    if (celdas_a_rellenar > 0 && numero_celda >= 0)
                    {
                        if (celdas_a_rellenar < numero_barco + 1) { celdas_a_rellenar++; }
                        tope_bucle = (posicion_barco == "horizontal") ? numero_celda - celdas_a_rellenar + 1 : numero_celda - ((celdas_a_rellenar - 1)*tablero_ancho);
                        //else { tope_bucle = (posicion_barco == "horizontal") ? numero_celda - celdas_a_rellenar : numero_celda - (celdas_a_rellenar*tablero_ancho); }
                        
                        //if (numero_celda >= 93 && numero_celda <= 99) { alert("bucle de "+numero_celda+" hasta "+tope_bucle+"..."); }
                        
                        for (var x = numero_celda; x >= tope_bucle && x >= 0; x -= incremento_bucle)
                        {
                            //if (numero_celda >= 93 && numero_celda <= 99) { alert("Para poner al barco " + eval(numero_barco+1) + " en " + numero_celda + " faltan " + celdas_a_rellenar + "["+posicion_barco+"] [se pone en "+x+"]"); }
                            //Calcula en que numero de fila se encuentra la celda enviada:
                            numero_fila_actual = parseInt(x/tablero_ancho);

                            //Calcula en que numero de columna se encuentra la celda enviada:
                            numero_columna_actual = x - (numero_fila_actual * tablero_ancho);

                            //Si el barco esta en horizontal y las columnas se acaban, sale del bucle:
                            if (posicion_barco == "horizontal" && numero_fila_actual < numero_fila) { break; }
                            //...pero si esta en vertical y las filas se acaban, sale del bucle tambien:
                            else if (posicion_barco == "vertical" && numero_columna_actual < numero_columna) { break; }

                            //Si la casilla ya esta ocupada y no es el barco que buscamos, sale de la funcion:
                            if (tablero_especulacion[x] != -1 && tablero_especulacion[x] != numero_barco+1) { return false; }
                            celdas_a_rellenar--;

                            //Si esta desmarcada la casilla de poder poner barcos adyacentes, comprobar si hay barcos adyacentes y si los hay no se puede poner:
                            if (mirar_adyacentes && !permitir_poner_barcos_adyacentes && hay_barcos_adyacentes_especulando(x, numero_barco)) { return false; }
                        }
                    }

                    //Si no se ha podido poner todo el barco en el tablero, sale de la funcion:
                    if (celdas_a_rellenar > 0) { return false; }
                   
                    //Si ha llegado hasta aqui se retorna que si se puede poner el barco:
                    return true;
                }


                //Funcion que define si un barco puede ponerse en el tablero en la posicion marcada o no:
                function se_puede_poner_barco(numero_celda, numero_barco, posicion_barco, propietario)
                {
                    //Si no hay barco arrastrandose y el que llama a la funcion es el usuario o el juego ya ha comenzado, sale de la funcion:
                    //if (propietario == "usuario" && barco_arrastrandose == 0 || se_ha_comenzado) { return false; }
                    if (se_ha_comenzado) { return false; }
                    
                    //Tener en cuenta el tama�o del barco arrastrandose, si esta en vertical o en horizontal y los barcos que crucen:
                    //Se define el incremento del bucle segun este el barco en vertical o en horizontal:
                    var incremento_bucle = (posicion_barco == "horizontal") ? 1 : tablero_ancho;
                    //Se define el tope del bucle segun este el barco en vertical o en horizontal:
                    var tope_bucle = (posicion_barco == "horizontal") ? numero_celda+numero_barco : numero_celda+(numero_barco*tablero_ancho);
                    //Si el barco no va a caber, sale de la funcion:
                    var numero_fila_actual = parseInt(numero_celda/tablero_ancho);
                    var numero_columna_actual = numero_celda - (numero_fila_actual * tablero_ancho);
                    var numero_fila = numero_fila_actual;
                    var numero_columna = numero_columna_actual;
                    if (posicion_barco == "horizontal" && numero_columna + numero_barco > tablero_ancho) { return false; }
                    else if (posicion_barco == "vertical" && numero_fila + numero_barco > tablero_alto) { return false; }
                    //Calcula si hay barcos que cruzan:
                    var celdas_a_rellenar = numero_barco;
                    for (var x = numero_celda; x < tope_bucle && x < tablero_ancho*tablero_alto; x += incremento_bucle)
                    {
                        //Calcula en que numero de fila se encuentra la celda enviada:
                        numero_fila_actual = parseInt(x/tablero_ancho);

                        //Calcula en que numero de columna se encuentra la celda enviada:
                        numero_columna_actual = x - (numero_fila_actual * tablero_ancho);

                        //Si el barco esta en horizontal y las columnas se acaban, sale del bucle:
                        if (posicion_barco == "horizontal" && numero_fila_actual > numero_fila) { break; }
                        //...pero si esta en vertical y las filas se acaban, sale del bucle tambien:
                        else if (posicion_barco == "vertical" && numero_columna_actual > numero_columna) { break; }

                        //Si la casilla ya esta ocupada, sale de la funcion:
                        if (propietario == "usuario" && tablero_usuario[x] != 0) { return false; }
                        else if (propietario == "ordenador" && tablero_ordenador[x] != 0) { return false; }
                        celdas_a_rellenar--;

                        //Si esta desmarcada la casilla de poder poner barcos adyacentes, comprobar si hay barcos adyacentes y si los hay no se puede poner:
                        if (!permitir_poner_barcos_adyacentes && hay_barcos_adyacentes(x, numero_barco, propietario)) { return false; }
                    }
                    //Si no se ha podido poner todo el barco en el tablero, sale de la funcion:
                    if (celdas_a_rellenar > 0) { return false; }
                    
                    //Si ha llegado hasta aqui se retorna que si se puede poner el barco:
                    return true;
                }


                //Funcio que comprueba si hay barcos adyacentes en el tablero especulado (retorna true si los hay):
                function hay_barcos_adyacentes_especulando(numero_celda, numero_barco)
                {
                    //Si el juego ya ha comenzado, sale de la funcion:

                    //CUIDADO: esta funcion no solo tiene en cuenta las adyacentes. Tambien tiene en cuenta la misma celda. Ademas, un barco puede ocupar mas de una celda. Como ventaja, se salta las celdas que tengan su mismo numero de barco. Esta funcion sirve porque presupone que nunca puede haber un barco en la misma celda en la que es llamada (en realidad si que lo puede haber pero se marcaran las celdas como que no se puede poner otro barco, asi que no es problema).

                    var numero_fila_inicial = numero_fila = parseInt(numero_celda/tablero_ancho);
                    var numero_columna_inicial = numero_columna = numero_celda - (numero_fila_inicial * tablero_ancho);
                    //Calcula si hay un barco adyacente o no:
                    var hay_adyacente = false;
                    //Ya que la variable y esta en un bucle que se repite tres veces, la declaramos aqui y no en el bucle:
                    var y;
                    //Recorre las tres filas:
                    for (var x = numero_celda - tablero_ancho - 1; x <= numero_celda + tablero_ancho - 1 && !hay_adyacente; x += tablero_ancho)
                    {
                        numero_fila = parseInt(x/tablero_ancho);
                        numero_columna = x - (numero_fila * tablero_ancho);
                        //Recorre las tres columnas:
                        for (y = x; y < x + 3 && numero_columna <= tablero_ancho; y++)
                        {
                            numero_fila = parseInt(y/tablero_ancho);
                            numero_columna = y - (numero_fila * tablero_ancho);
                            //if (numero_columna >= tablero_ancho) { break; }
                            if (y < 0 || y >= tablero_ancho*tablero_alto) { continue; }
                            //if (y >= 0 && y < tablero_ancho*tablero_alto && (numero_columna <= numero_columna_inicial+1 && numero_columna >= numero_columna_inicial-1 && numero_columna >= 0 && numero_columna <= tablero_ancho) && (numero_fila <= numero_fila_inicial+1 && numero_fila >= numero_fila_inicial-1))
                            else if ((numero_columna <= numero_columna_inicial+1 && numero_columna >= numero_columna_inicial-1) && (numero_fila <= numero_fila_inicial+1 && numero_fila >= numero_fila_inicial-1))
                            {
                                if (tablero_especulacion[y] != -1 && tablero_especulacion[y] != numero_barco+1 && tablero_especulacion[y] != "B") { hay_adyacente = true; break; }
                            }
                        }
                    }
                    
                    //Retorna si hay o no barcos adyacentes:
                    return hay_adyacente;
                }
                
                
                //Funcio que comprueba si hay barcos adyacentes (retorna true si los hay):
                function hay_barcos_adyacentes(numero_celda, numero_barco, jugador)
                {
                    //Si el juego ya ha comenzado, sale de la funcion:

                    //CUIDADO: esta funcion no solo tiene en cuenta las adyacentes. Tambien tiene en cuenta la misma celda. Ademas, un barco puede ocupar mas de una celda. Como ventaja, se salta las celdas que tengan su mismo numero de barco. Esta funcion sirve porque presupone que nunca puede haber un barco en la misma celda en la que es llamada (en realidad si que lo puede haber pero se marcaran las celdas como que no se puede poner otro barco, asi que no es problema).

                    var numero_fila_inicial = numero_fila = parseInt(numero_celda/tablero_ancho);
                    var numero_columna_inicial = numero_columna = numero_celda - (numero_fila_inicial * tablero_ancho);
                    //Calcula si hay un barco adyacente o no:
                    var hay_adyacente = false;
                    //Ya que la variable y esta en un bucle que se repite tres veces, la declaramos aqui y no en el bucle:
                    var y;
                    //Recorre las tres filas:
                    for (var x = numero_celda - tablero_ancho - 1; x <= numero_celda + tablero_ancho - 1 && !hay_adyacente; x += tablero_ancho)
                    {
                        numero_fila = parseInt(x/tablero_ancho);
                        numero_columna = x - (numero_fila * tablero_ancho);
                        //Recorre las tres columnas:
                        for (y = x; y < x + 3 && numero_columna <= tablero_ancho; y++)
                        {
                            numero_fila = parseInt(y/tablero_ancho);
                            numero_columna = y - (numero_fila * tablero_ancho);
                            //if (numero_columna >= tablero_ancho) { break; }
                            if (y < 0 || y >= tablero_ancho*tablero_alto) { continue; }
                            //if (y >= 0 && y < tablero_ancho*tablero_alto && (numero_columna <= numero_columna_inicial+1 && numero_columna >= numero_columna_inicial-1 && numero_columna >= 0 && numero_columna <= tablero_ancho) && (numero_fila <= numero_fila_inicial+1 && numero_fila >= numero_fila_inicial-1))
                            else if ((numero_columna <= numero_columna_inicial+1 && numero_columna >= numero_columna_inicial-1) && (numero_fila <= numero_fila_inicial+1 && numero_fila >= numero_fila_inicial-1))
                            {
                                if (jugador == "usuario")
                                {
                                    if (tablero_usuario[y] != 0 && tablero_usuario[y] != numero_barco) { hay_adyacente = true; break; }
                                }
                                else
                                {
                                    if (tablero_ordenador[y] != 0 && tablero_ordenador[y] != numero_barco) { hay_adyacente = true; break; }
                                }
                            }
                        }
                    }
                    
                    //Retorna si hay o no barcos adyacentes:
                    return hay_adyacente;
                }
                

                //Funcion que rota el barco:
                function rotar_barco()
                {
                    //Si el juego esta bloqueado, sale de la funcion:
                    if (juego_bloqueado) { return; }

                    var prioridad = 0;
                    
                    //Si no se esta arrastrando ningun barco, sale de la funcion:
                    if (barco_arrastrandose == 0) { return; }

                    //Si se esta arrastrando el horizontal, se cambia al vertical:
                    if (document.getElementById("barco_" + barco_arrastrandose).style.visibility == "visible")
                    {
                        //Se pone igual top y left al vertical que al horizontal:
                        document.getElementById("barco_" + barco_arrastrandose + "_vertical").style.top = document.getElementById("barco_" + barco_arrastrandose).style.top;
                        document.getElementById("barco_" + barco_arrastrandose + "_vertical").style.left = document.getElementById("barco_" + barco_arrastrandose).style.left;
                        //Oculta el horizontal y hace visible el vertical:
                        document.getElementById("barco_" + barco_arrastrandose).style.visibility = "hidden";
                        document.getElementById("barco_" + barco_arrastrandose + "_vertical").style.visibility = "visible";
                        //Se pone el barco en horizontal arriba del todo, ahora que es invisible:
                        document.getElementById("barco_" + barco_arrastrandose).style.top = "0px";
                        //Da mas prioridad al barco, para que en caso de estar encima del tablero haga un onmouseout y luego vuelve a dejar la misma prioridad para que hagaun onmouseover:
                        //prioridad = document.getElementById("barco_" + barco_arrastrandose + "_vertical").style.zIndex;
                        //document.getElementById("barco_" + barco_arrastrandose + "_vertical").style.zIndex = 5;
                        //setTimeout('document.getElementById("barco_" + barco_arrastrandose + "_vertical").style.zIndex = ' + prioridad + ';', 10000);
                    }
                    //...y si no, al reves:
                    else if (document.getElementById("barco_" + barco_arrastrandose + "_vertical").style.visibility == "visible")
                    {
                        //Se pone igual top y left al horizontal que al vertical:
                        document.getElementById("barco_" + barco_arrastrandose).style.top = document.getElementById("barco_" + barco_arrastrandose + "_vertical").style.top;
                        document.getElementById("barco_" + barco_arrastrandose).style.left = document.getElementById("barco_" + barco_arrastrandose + "_vertical").style.left;
                        //Oculta el vertical y hace visible el horizontal:
                        document.getElementById("barco_" + barco_arrastrandose + "_vertical").style.visibility = "hidden";
                        document.getElementById("barco_" + barco_arrastrandose).style.visibility = "visible";
                        //Se pone el barco en vertical arriba del todo, ahora que es invisible:
                        document.getElementById("barco_" + barco_arrastrandose + "_vertical").style.top = "0px";
                        //Da mas prioridad al barco, para que en caso de estar encima del tablero haga un onmouseout y luego vuelve a dejar la misma prioridad para que hagaun onmouseover:
                        //prioridad = document.getElementById("barco_" + barco_arrastrandose).style.zIndex;
                        //document.getElementById("barco_" + barco_arrastrandose).style.zIndex = 5;
                        //setTimeout('document.getElementById("barco_" + barco_arrastrandose).style.zIndex = ' + prioridad + ';', 10000);
                    }

                    //Si aun hay celdas marcadas, se vuelven a marcar con el barco en la nueva posicion:
                    if (celdas_marcadas[0] >= 0) { marcar_coordenada(ultima_celda_indicada, "usuario"); }
                    
                    //Se devuelve false para que no salga el menu contextual (por si se habia apretado el boton derecho):
                    return false;
                }
                


                //Funcion que muestra u oculta las opciones:
                function mostrar_ocultar_opciones()
                {
                    //Si el juego esta bloqueado, sale de la funcion:
                    if (juego_bloqueado) { return; }

                    //Si el menu esta visible, se oculta:
                    //if (document.getElementById("menu_opciones").style.visibility == "visible") { document.getElementById("menu_opciones").style.visibility = "hidden"; document.getElementById("menu_opciones_sombra").style.visibility = "hidden"; document.getElementById("menu").title = "Abrir opciones"; document.getElementById("zona_juego_usuario_invisible").style.visibility = document.getElementById("zona_juego_ordenador_invisible").style.visibility = "visible"; }
                    if (document.getElementById("menu_opciones").style.visibility == "visible") { document.getElementById("menu_opciones").style.visibility = "hidden"; document.getElementById("menu_opciones_sombra").style.visibility = "hidden"; document.getElementById("menu").title = "Abrir opciones"; }
                    //...pero si esta oculto, se muestra:
                    else
                    {
                        //Se ponen las opciones en el menu de opciones:
                        document.getElementById("tablero_ancho").value = tablero_ancho;
                        document.getElementById("tablero_alto").value = tablero_alto;
                        document.getElementById("permitir_bombardear_lo_bombardeado").checked = permitir_bombardear_lo_bombardeado;
                        document.getElementById("permitir_poner_barcos_adyacentes").checked = permitir_poner_barcos_adyacentes;
                        document.getElementById("marcar_bombardeado").checked = marcar_bombardeado;
                        var numero_primer_jugador = (primer_jugador == "usuario") ? 0 : 1;
                        document.getElementById("primer_jugador").options[numero_primer_jugador].selected = true;
                        document.getElementById("dificultad").options[dificultad].selected = true;

                        document.getElementById("menu_opciones").style.visibility = "visible"; document.getElementById("menu_opciones_sombra").style.visibility = "visible"; document.getElementById("menu").title = "Cerrar opciones";
                    }
                }

                
                //Funcion que marca las coordenadas:
                function marcar_coordenada(numero_celda, jugador)
                {
                    //Si el juego esta bloqueado, sale de la funcion:
                    if (juego_bloqueado) { return; }

                    //Se desmarcan las coordenadas de la ultima celda indicada, siempre que haya habido una ultima:
                    if (ultima_celda_indicada >= 0 && ultima_celda_indicada < tablero_ancho * tablero_alto)
                    {
                        var ultimo_numero_fila = parseInt(ultima_celda_indicada/tablero_ancho);
                        var ultimo_numero_columna = ultima_celda_indicada - (ultimo_numero_fila * tablero_ancho);
                        var ultimo_numero_fila_invertida = tablero_alto - ultimo_numero_fila - 1;
                        document.getElementById("y_" + ultimo_numero_fila_invertida).style.background = "#000011";
                        //Como no sabemos si la ultima casilla indicada fue en la zona de juego del usuario o del ordenador, desmarcamos las dos por si acaso:
                        document.getElementById("x_" + ultimo_numero_columna + "_usuario").style.background = "#000011";
                        document.getElementById("x_" + ultimo_numero_columna + "_ordenador").style.background = "#000011";
                    }
                    else if (ultima_celda_indicada >= 0 && ultima_celda_indicada >= tablero_ancho * tablero_alto) { ultima_celda_indiacada = -1; return; } //Si ha entrado en esta funcion con una celda mas grande que el mapa, sale de ella.

                    //Desmarca las celdas marcadas anteriormente, si la matriz no es -1:
                    if (celdas_marcadas[0] >= 0)
                    {
                        for (var x = 0; x < celdas_marcadas.length; x++)
                        {
                            if (celdas_marcadas[x] < 0) { break; }
                            document.getElementById('celda_' + celdas_marcadas[x] + '_usuario').style.background = "#6600ff";
                            celdas_marcadas[x] = -1;
                        }
                    }

                    //Si se ha enviado la celda -1, sale de la funcion ya que solo queriamos dejar de marcar las coordenadas o hace lo mismo si el numero de celda es imposible:
                    if (numero_celda == -1 || numero_celda >= tablero_alto * tablero_ancho) { return; }

                    //Calcula en que numero de fila se encuentra la celda enviada:
                    var numero_fila = parseInt(numero_celda/tablero_ancho);
                    //Calcula en que numero de columna se encuentra la celda enviada:
                    var numero_columna = numero_celda - (numero_fila * tablero_ancho);

                    //Se marca la celda escogida:
                    if (!se_ha_comenzado)
                    {
                        if (jugador == "usuario" && tablero_usuario[numero_celda] == 0) { document.getElementById("celda_" + numero_celda + "_" + jugador).style.background = "#00ff00"; }
                        else { document.getElementById("celda_" + numero_celda + "_" + jugador).style.background = "#ff0000"; }
                    }
//                    else if (jugador_actual == "usuario")
                    else
                    {
                        if (jugador == "ordenador" && (permitir_bombardear_lo_bombardeado || tablero_ordenador[numero_celda] != "B" && tablero_ordenador[numero_celda] != "X")) { document.getElementById("celda_" + numero_celda + "_" + jugador).style.background = "#00ff00"; }
                        else { document.getElementById("celda_" + numero_celda + "_" + jugador).style.background = "#ff0000"; }
                    }
//                    else
//                    {
//                        document.getElementById("celda_" + numero_celda + "_" + jugador).style.background = "#ff0000";
//                    }
                    
                    //Se marcan las coordenadas de la celda:
                    var numero_fila_invertida = tablero_alto - numero_fila - 1;
                    document.getElementById("y_" + numero_fila_invertida).style.background = "#ff0000";
                    document.getElementById("x_" + numero_columna + "_" + jugador).style.background = "#ff0000";
                    
                    //Si se esta arrastrando un barco y se esta encima del tablero del usuario, se marcan las coordenadas que ocupa el barco:
                    if (barco_arrastrandose != 0 && jugador == "usuario")
                    {
                        //Se define si el barco esta en horizontal o en vertical:
                        var posicion_barco = (document.getElementById("barco_" + barco_arrastrandose).style.visibility == "visible") ? "horizontal" : "vertical";
                        //Se define el incremento del bucle segun este el barco en vertical o en horizontal:
                        var incremento_bucle = (posicion_barco == "horizontal") ? 1 : tablero_ancho;
                        //Se define el tope del bucle segun este el barco en vertical o en horizontal:
                        var tope_bucle = (posicion_barco == "horizontal") ? numero_celda+barco_arrastrandose : numero_celda+(barco_arrastrandose*tablero_ancho);
                        //Cambiar el color de marcar si el barco no cabe:
                        var color_marcado = (se_puede_poner_barco(numero_celda, barco_arrastrandose, posicion_barco, "usuario")) ? "#00ff00" : "#ff0000";
                        //Bucle que se acaba cuando el barco se acaba o la columna o fila se acaba (depende de si el barco esta en horizontal o en vertical):
                        var indice_contador = 0;
                        var numero_fila_actual = 0;
                        var numero_columna_actual = 0;
                        for (x = numero_celda; x < tope_bucle && x < tablero_ancho*tablero_alto; x += incremento_bucle)
                        {
                            //Calcula en que numero de fila se encuentra la celda enviada:
                            numero_fila_actual = parseInt(x/tablero_ancho);

                            //Calcula en que numero de columna se encuentra la celda enviada:
                            numero_columna_actual = x - (numero_fila_actual * tablero_ancho);

                            //Si el barco esta en horizontal y las columnas se acaban, sale del bucle:
                            if (document.getElementById("barco_" + barco_arrastrandose).style.visibility == "visible" && numero_fila_actual > numero_fila) { break; }
                            //...pero si esta en vertical y las filas se acaban, sale del bucle tambien:
                            else if (document.getElementById("barco_" + barco_arrastrandose + "_vertical").style.visibility == "visible" && numero_columna_actual > numero_columna) { break; }

                            //Se marca con el color elegido:
                            document.getElementById('celda_' + x + '_usuario').style.background = color_marcado;

                            //Guarda en una matriz las celdas marcadas, y la proxima vez las desmarcara antes de marcar las siguientes:
                            celdas_marcadas[indice_contador] = x;
                            indice_contador++;
                        }
                    }
                    
                    //Se almacena la ultima celda indicada en coordenadas:
                    ultima_celda_indicada = numero_celda;
                }

                
                //Funcion que aplica las opciones:
                function aplicar_opciones()
                {
                    //Si el juego esta bloqueado, sale de la funcion:
                    if (juego_bloqueado) { return; }

                    //Se guardan las opciones de configuracion enviadas en variables preliminares:
                    var tablero_ancho_enviado = parseInt(document.getElementById("tablero_ancho").value);
                    var tablero_alto_enviado = parseInt(document.getElementById("tablero_alto").value);
                    var permitir_bombardear_lo_bombardeado_enviado = document.getElementById("permitir_bombardear_lo_bombardeado").checked;
                    var permitir_poner_barcos_adyacentes_enviado = document.getElementById("permitir_poner_barcos_adyacentes").checked;
                    var marcar_bombardeado_enviado = document.getElementById("marcar_bombardeado").checked;
                    var primer_jugador_enviado = document.getElementById("primer_jugador").value;
                    var dificultad_enviado = document.getElementById("dificultad").selectedIndex;

                    //Si las opciones enviadas ya son las establecidas, sale de la funcion:
                    if (tablero_ancho_enviado == tablero_ancho && tablero_alto_enviado == tablero_alto && permitir_bombardear_lo_bombardeado_enviado == permitir_bombardear_lo_bombardeado && permitir_poner_barcos_adyacentes_enviado == permitir_poner_barcos_adyacentes && marcar_bombardeado_enviado == marcar_bombardeado && primer_jugador_enviado == primer_jugador && dificultad_enviado == dificultad) { return; }

                    //Se comprueba que todas se hayan enviado de forma correcta:
                    var errores = "";
                    if (isNaN(tablero_ancho_enviado) || tablero_ancho_enviado == "") { errores += "\nEl ancho del tablero debe contener un valor numerico."; }
                    else if (tablero_ancho_enviado < tablero_ancho_minimo || tablero_ancho_enviado > tablero_ancho_maximo) { errores += "\nEl ancho del tablero debe estar comprendido entre " + tablero_ancho_minimo + " y " + tablero_ancho_maximo; }
                    if (isNaN(tablero_alto_enviado) || tablero_alto_enviado == "") { errores += "\nEl alto del tablero debe contener un valor numerico."; }
                    else if (tablero_alto_enviado < tablero_alto_minimo || tablero_alto_enviado > tablero_alto_maximo) { errores += "\nEl alto del tablero debe estar comprendido entre " + tablero_alto_minimo + " y " + tablero_alto_maximo; }
                    
                    //Si ha habido algun error, se muestra y sale de la funcion:
                    if (errores != "")
                    {
                        if (alerts_activados) { alert("No pueden aplicarse las opciones porque han habido los siguientes errores:" + errores); }
                        else
                        {
                                        //var expresion_regular = new RegExp("\n", "g");
			                            //errores = errores.replace(expresion_regular, "<br>");
                                        //mostrar_alerta("No pueden aplicarse las opciones porque han habido los siguientes errores:<br>" + errores);
                                        mostrar_alerta("No pueden aplicarse las opciones porque han habido los siguientes errores:<br>" + errores.replace(/[\n]/g, "<br>"), false);
                        }
                        return;
                    }
                    //...pero si todo esta correcto, se pide confirmacion y si acepta se procede a aplicar las opciones:
                    else if (!alerts_activados || alerts_activados && confirm("Se va a proceder a aplicar las opciones escogidas. El juego actual va a perderse."))
                    {
                        //Calcula si el alto y ancho de los tableros es el mismo o no:
                        var mismos_tableros = (tablero_ancho == tablero_ancho_enviado && tablero_alto == tablero_alto_enviado) ? true : false;

                        //Se aplican las opciones:
                        tablero_ancho = tablero_ancho_enviado;
                        tablero_alto = tablero_alto_enviado;
                        permitir_bombardear_lo_bombardeado = permitir_bombardear_lo_bombardeado_enviado;
                        permitir_poner_barcos_adyacentes = permitir_poner_barcos_adyacentes_enviado;
                        marcar_bombardeado = marcar_bombardeado_enviado;
                        primer_jugador = primer_jugador_enviado;
                        dificultad = dificultad_enviado;

                        //Se muestra el mensaje de espera:
                        mostrar_mensaje("Cargando...");
                        
                        //Se reinicia el juego para que las nuevas opciones hagan efecto:
                        setTimeout("iniciar_juego(" + mismos_tableros + ");", 10); //Se inicia el juego despues de unos milisegundos para dar dar tiempo a mostrar el mensaje de espera.
                    }
                    //...pero si no acepta la confirmacion, no aplica las opciones:
                    else { return; }
                }


                //Funcion que calcula las celdas validas para poner un barco (solo las libres, al menos de momento):
                function calcular_celdas_validas(tablero)
                {
                    var celdas_libres = new Array();
                    var y = 0;
                    
                    //Busca las celdas libres:
                    for (var x = 0; x < tablero.length; x++)
                    {
                        if (tablero[x] == 0) { celdas_libres[y] = x; y++; }
                    }
                    
                    return celdas_libres;
                }


                //Funcion que ordena una matriz de mayor a menor (metodo de la burbuja):
                function ordenar_matriz(matriz)
                {
                    var y = 0, temp;
                    for (var x = matriz.length-1; x > 0; x--)
                    {
                        for (y = 0; y < x; y++)
                        {
                            if (matriz[y] < matriz[y+1]) { temp = matriz[y]; matriz[y] = matriz[y+1]; matriz[y+1] = temp; }
                        }
                    }
                    
                    return matriz;
                }

                
                //Funcion que pone o borra un barco especifico del ordenador:
                function poner_borrar_barco_ordenador(celda_a_probar, numero_barco, posicion_barco, numero_fila_inicial, numero_columna_inicial, borrar)
                {
                    var x, numero_fila_actual, numero_columna_actual;
                    if (posicion_barco == "horizontal")
                    {
                        for (x = celda_a_probar; x < celda_a_probar + numero_barco; x++)
                        {
                            numero_fila_actual = parseInt(x/tablero_ancho);
                            if (numero_fila_actual != numero_fila_inicial) { break; }
                            if (borrar) { tablero_ordenador[x] = 0; }
                            else { tablero_ordenador[x] = numero_barco; }
//                            if (tablero_ordenador[x] != 0) { document.getElementById('celda_' + x + '_ordenador').style.background = '#ff0000'; document.getElementById('celda_' + x + '_ordenador_invisible').innerHTML = numero_barco; }
//                            else { document.getElementById('celda_' + x + '_ordenador').style.background = '#0000ff'; document.getElementById('celda_' + x + '_ordenador_invisible').innerHTML = 0; }
                        }
                    }
                    else
                    {

                        for (x = celda_a_probar; x < celda_a_probar + (tablero_ancho * numero_barco) && x < tablero_ancho*tablero_alto; x += tablero_ancho)
                        {
                            numero_fila_actual = parseInt(x/tablero_ancho); 
                            numero_columna_actual = x - (numero_fila_actual * tablero_ancho);
                            if (numero_columna_actual != numero_columna_inicial) { break; }
                            if (borrar) { tablero_ordenador[x] = 0; }
                            else { tablero_ordenador[x] = numero_barco; }
//                            if (tablero_ordenador[x] != 0) { document.getElementById('celda_' + x + '_ordenador').style.background = '#ff0000'; document.getElementById('celda_' + x + '_ordenador_invisible').innerHTML = numero_barco; }
//                            else { document.getElementById('celda_' + x + '_ordenador').style.background = '#0000ff'; document.getElementById('celda_' + x + '_ordenador_invisible').innerHTML = 0; }
                        }
                    }
                }

                
                //Funcion recursiva que utiliza el ordenador para poner sus barcos:
                function poner_barco_ordenador(numero_barco)
                {
                    var quedan_celdas_por_probar = true;
                    var celda_a_probar;
                    var posicion_celda_a_probar;
                    //Matriz con las celdas libres:
                    var celdas_libres = new Array(); //QUIZA SE OPTIMIZARIA DEVOLVIENDO LAS CELDAS EN LAS KE EL BARKO PUEDE PONERSE I NO LAS LIBRES!!!!! (y la funcion ke se le pasara por argumento la posicion del barko, en vertikal o en horizontal, i tener dos matrices, una para kada posicion. entonces kalkular aleatoriamente una posicion i luego probar todas las celdas en las k se puede poner. si no se puede ninguna, probar kon la otra posicion.)
                    celdas_libres = calcular_celdas_validas(tablero_ordenador);
                    var celdas_por_probar = celdas_libres.length;
                    var barco_rotado = false;
                    
                    var posicion_barco;
                    var se_pueden_los_siguientes;
                    var numero_fila_inicial, numero_columna_inicial, numero_fila_actual, numero_columna_actual;
                    
                    var x;
                    while (celdas_por_probar > 0)
                    {
                        //Ordena las celdas libres y pone las ya probadas (-1) al final:
                        celdas_libres = ordenar_matriz(celdas_libres);
                        
                        //Calcula una posicion aleatoria 
                        posicion_celda_a_probar = parseInt(Math.random() * celdas_por_probar)
                        celda_a_probar = celdas_libres[posicion_celda_a_probar];
                        
                        //Resta una celda por probar y justo la que se ha probado:
                        celdas_por_probar--;
                        celdas_libres[posicion_celda_a_probar] = -1;

                        //Calcula la fila y la columna de la celda escogida:
                        numero_fila_inicial = parseInt(celda_a_probar/tablero_ancho);
                        numero_columna_inicial = celda_a_probar - (numero_fila_inicial * tablero_ancho);

                        //Calcula si en horizontal o en vertical:
                        posicion_barco = (parseInt(Math.random() * 2) == 1) ? "vertical" : "horizontal";
                        
                        //Calcula si el barco puede ponerse en la celda calculada:
                        se_puede_poner = se_puede_poner_barco(celda_a_probar, numero_barco, posicion_barco, "ordenador");
                        
                        barco_rotado = false;
                        //Si no se puede poner, cambia la posicion del barco y lo vuelve a intentar:
                        if (!se_puede_poner) { barco_rotado = true; posicion_barco = (posicion_barco == "horizontal") ? "vertical" : "horizontal"; se_puede_poner = se_puede_poner_barco(celda_a_probar, numero_barco, posicion_barco, "ordenador"); }
                        //Si se puede pone el barco:
                        if (se_puede_poner)
                        {
                            //Pone el barco:
                            poner_borrar_barco_ordenador(celda_a_probar, numero_barco, posicion_barco, numero_fila_inicial, numero_columna_inicial, false);
//alert("barco "+ numero_barco+" se puede en "+celda_a_probar+" en posicion "+posicion_barco);
                            
                            //Si es el barco 1, devuelve true:
                            if (numero_barco == 1) { return true; }
                            //...pero si no era el barco 1, llama recursivamente con el barco inferior:
                            else
                            {
                                se_pueden_los_siguientes = poner_barco_ordenador(numero_barco - 1);
                                
                                //Si se pueden poner los siguientes, devuelve true:
                                if (se_pueden_los_siguientes) { return true; }
                                //...pero si no se pueden poner los siguientes:
                                else
                                {   
                                    //Borra el barco:
                                    poner_borrar_barco_ordenador(celda_a_probar, numero_barco, posicion_barco, numero_fila_inicial, numero_columna_inicial, true);
//alert("barco "+ numero_barco+" BORRADO en "+celda_a_probar+" en posicion "+posicion_barco);
                                    
                                    //Si no se ha rotado, prueba a rotarlo y a ver si se puede:
                                    if (!barco_rotado)
                                    {

                                    
                                        barco_rotado = true;
                                        posicion_barco = (posicion_barco == "horizontal") ? "vertical" : "horizontal";

                                        //Calcula si se puede poner:
                                        se_puede_poner = se_puede_poner_barco(celda_a_probar, numero_barco, posicion_barco, "ordenador");

                                        if (se_puede_poner)
                                        {
                                            //Pone el barco con la nueva posicion:
                                            poner_borrar_barco_ordenador(celda_a_probar, numero_barco, posicion_barco, numero_fila_inicial, numero_columna_inicial, false);
//alert("barco "+ numero_barco+" se puede en "+celda_a_probar+" en posicion "+posicion_barco);
                                            
                                            se_pueden_los_siguientes = poner_barco_ordenador(numero_barco - 1);
                                
                                            //Si se pueden poner los siguientes, devuelve true:
                                            if (se_pueden_los_siguientes) { return true; }
                                            //...pero si no, borra el barco:
                                            else
                                            {
                                                //Borra el barco puesto:
                                                poner_borrar_barco_ordenador(celda_a_probar, numero_barco, posicion_barco, numero_fila_inicial, numero_columna_inicial, true);
//alert("barco "+ numero_barco+" BORRADO de "+celda_a_probar+" en posicion "+posicion_barco);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    //Si ha llegado hasta aqui es que no se puede poner el barco, asi que devuelve false:
                    return false;
                }


                //Funcion que inicia el juego en si (la batalla):
                function comenzar_batalla()
                {
                    //Si el juego esta bloqueado, sale de la funcion:
                    if (juego_bloqueado) { return; }

                    //Si se ha comenzado ya el juego, sale de la funcion:
                    if (se_ha_comenzado) { return; }
                    
                    //Oculta el boton de comenzar la batalla:
                    document.getElementById("boton_comenzar_juego").style.visibility = "hidden";
                    
                    //Pone el texto de barcos hundidos en el div de los barcos:
                    document.getElementById("barcos").innerHTML = "&nbsp; Barcos hundidos:";
                    
                    //Mostrar el mensaje de espera:
                    mostrar_mensaje("Procesando...");

                    //Le da la prioridad inicial a todos los barcos puestos (para que la bomba y las explosiones no se situen debajo de ellos):
                    for (var x = 1; x <= 5; x++) { document.getElementById("barco_" + x).style.zIndex = document.getElementById("barco_" + x + "_vertical").style.zIndex = 2; }
                    //document.getElementById("zona_juego_usuario").style.zIndex = document.getElementById("zona_juego_ordenador").style.zIndex = 3;

                    //Hace que el ordenador ponga sus barcos:
                    if (!poner_barco_ordenador(5))
                    {
                        if (alerts_activados) { alert("No se han podido colocar los barcos del ordenador. Intenta ampliar el tablero y vuelve a intentarlo."); iniciar_juego(true); }
                        else { mostrar_alerta("No se han podido colocar los barcos del ordenador. Intenta ampliar el tablero y vuelve a intentarlo.", true); }
                    }

                    //Si se ha definido ver al principio donde ha puesto los barcos el ordenador, se muestran:
                    for (var x = 0; x < tablero_ordenador.length && ver_barcos_ordenador; x++) { if (tablero_ordenador[x] != 0) { document.getElementById("celda_" + x + "_ordenador").style.background = "#ff0000"; } }

//for (x = 0; x < tablero_ordenador.length; x++) { if (tablero_ordenador[x] != 0) { document.getElementById("celda_" + x + "_ordenador").style.background = "#ff0000"; } }
                    
                    //El ordenador pone sus barcos (tener en cuenta si se puede o no adyacentes):
//                    var barcos_puestos = 0, numero_celda, numero_barco, posicion_barco, se_puede_poner = false, incremento_celda, y;
                    
//                    while (barcos_puestos < 5)
//                    {
                        //Guarda el numero de barco que toca poner:
//                        numero_barco = barcos_puestos + 1;

                        //Calcula una posicion aleatoria para la primera celda del barco (siempre que este libre):
//                        do { numero_celda = parseInt(Math.random() * (tablero_alto*tablero_ancho)); } while (tablero_ordenador[numero_celda] != 0);

                        //Calcula aleatoriamente si poner el barco en horizontal o en vertical:
//                        posicion_barco = (parseInt(Math.random() * 2) == 1) ? "vertical" : "horizontal";

                        //Si se ha escogido poner el barco en horizontal y no va a caber, se pone en vertical:
                        
                        //Si se ha escogido poner el barco en vertical y no va a caber, se vuelve a realizar el bucle:
                        

                        //Calcula si se puede poner el barco (ya tiene en cuenta si se puede o no adyacentes):
//                        se_puede_poner = se_puede_poner_barco(numero_celda, numero_barco, posicion_barco, "ordenador");
                        
                        //Si todo ha ido bien, lo pone:
//                        if (se_puede_poner)
//                        {
//                            incremento_celda = (posicion_barco == "horizontal") ? 1 : tablero_ancho;
//                            y = numero_celda;
//                            for (x = 0; x < numero_barco; x++) { tablero_ordenador[y] = numero_barco;  document.getElementById("celda_" + y + "_ordenador").style.background = "#ff0000"; y += incremento_celda; }
//                            barcos_puestos++;
//                        }
//                    }
                  
                    //Define como que ha comenzado el juego:
                    se_ha_comenzado = true;
                    
                    //Oculta el mensaje de espera:
                    mostrar_mensaje("");
                    
                    //Otorga el control al primer jugador:
                    otorgar_control(primer_jugador);
                }


                //Funcion que otorga el control al jugador correspondiente:
                function otorgar_control(jugador)
                {
                    //Si el juego esta bloqueado, sale de la funcion:
                    if (juego_bloqueado) { return; }

                    //Calcula si se ha acabado el juego y si es asi, sale de la funcion:
                    var ultimo_atacante = (jugador == "ordenador") ? "usuario" : "ordenador";
                    if (calcular_fin_de_juego(ultimo_atacante)) { return; }

                    //Pasa el turno al jugador que le toca:
                    jugador_actual = jugador;

                    //Si se ha seleccionado al ordenador:
                    if (jugador == "ordenador")
                    {
                        //Muestra el mensaje de espera:
                        mostrar_mensaje("Procesando...");
                        
                        //Oculta la bomba del usuario:
                        document.getElementById("bomba").style.visibility = "hidden";
                        
                        //Despues de un instante, calcula la mejor posicion y la bombardea y luego oculta el mensaje de espera:
                        setTimeout('var mejor_posicion = calcular_mejor_posicion(); bombardear(mejor_posicion, "ordenador");', 10);
                    }
                    //...pero si no, otorga el control al usuario:
                    else
                    {
                        //Hace que arrastre la bomba, si no esta el menu de opciones activo:
                        if (document.getElementById("menu_opciones").style.visibility == "hidden") { document.getElementById("bomba").style.visibility = "visible"; }

                        //Si aun hay celdas marcadas, se vuelven a marcar con el barco en la nueva posicion:
                        //if (celdas_marcadas[0] >= 0) { marcar_coordenada(ultima_celda_indicada, "ordenador"); }
                    }
                }


                //Funcion que calcula la mejor posicion que puede bombardear el ordenador (teniendo en cuenta su inteligencia):
                function calcular_mejor_posicion()
                {
                    //Variable que guardara la mejor celda a bombardear:
                    var mejor_celda = -1;
                    
                    //Variable que define la probabilidad con la que se hace algo inteligente o no (depende de la dificultad):
                    var se_hace = 0;
                    //Variable que calcula aleatoriamente un porcentaje de 1 a 100 y si la probabilidad de se_hace es mayor o igual, se hace:
                    var probabilidad = 100;

                    //Calcula la mejor posicion a bombardear (TENER EN CUENTA QUE NO ESTE BOMBARDEADA YA!!!):
                    //mejor_celda = parseInt(Math.random() * (tablero_alto*tablero_ancho)); //BORRAR ESTO!!!
                    
                    //Guarda el barco que se elige para ir a por el:
                    var barco_elegido = -1;                    
                    
                    //Guarda el numero del barco buscado:
                    var barco_buscado = barco_elegido;
                    
                    //Guarda la posicion del barco que se busca:
                    var posicion_barco_buscado;
                    
                    //Se recorren todos los barcos, y de los que han sido tocados se escoge para buscar el mas grande:
                    var sabe_que_barco_buscar = false;
                    for (var x = 4; x >= 0; x--)
                    {
                        //Si ha sido tocado pero no hundido, se escoge para ser buscado y sale del bucle:
                        if (barcos["usuario"][x] < x+1 && barcos["usuario"][x] > 0) { barco_buscado = x; sabe_que_barco_buscar = true; break; }
                    }
                    
                    //Si sabe que barco buscar:
                    if (sabe_que_barco_buscar)
                    {
                        //FALTA: a las celdas de la cruz que se descarten o a la unica que se utilice, ponerle -1 (ia ke va a ser bombardeada, o a lo mejor es mejor no poner -1??). Deskartar celdas kon la funcion ke averigua si se puede poner un barko o no i si la celda de la kruz ia ha esta bombardeada.
                        
                        //alert("se ke barko buskar i es el " + barco_buscado + "("+cruz_barco[barco_buscado]["vertical"][0]+", "+cruz_barco[barco_buscado]["vertical"][1]+", "+cruz_barco[barco_buscado]["horizontal"][0]+", "+cruz_barco[barco_buscado]["horizontal"][1]+")");
                        posicion_barco_buscado = posicion_barco[barco_buscado];
//mostrar_mensaje("buscando: " + barco_buscado + " [buskado]");
                        //Si no sabe en que posicion esta el barco, lo calcula aleatoriamente:
                        if (posicion_barco_buscado == "?") { posicion_barco_buscado = (parseInt(Math.random() * 2) == 1) ? "vertical" : "horizontal"; }
                        //Elige una de las esquinas, siempre que aun no hayan sido bombardeadas:
                        if (dificultad <= 0) { se_hace = 75; }
                        else if (dificultad == 1) { se_hace = 90; }
                        else { se_hace = 100; }
                        probabilidad = parseInt(Math.random() * 100) + 1;
                        if (se_hace >= probabilidad)
                        {
                            var numero_posicion = parseInt(Math.random() * 2);
                            mejor_celda = cruz_barco[barco_buscado][posicion_barco_buscado][numero_posicion]; //Se escoge la celda superior o la de la izquierda.
                            if (mejor_celda == -1 || tablero_especulacion[mejor_celda] != -1 || !se_puede_poner_barco_especulando(mejor_celda, barco_buscado, posicion_barco_buscado, true)) { numero_posicion = (numero_posicion == 0) ? 1 : 0; mejor_celda = cruz_barco[barco_buscado][posicion_barco_buscado][numero_posicion]; } //Si no se puede o ya se ha bombardeado, se escoge la inferior o la de la derecha.
                            //Si aun asi no se puede y no se sabe la posicion del barco, se escoge otra.
                            if ((mejor_celda == -1  || tablero_especulacion[mejor_celda] != -1 || !se_puede_poner_barco_especulando(mejor_celda, barco_buscado, posicion_barco_buscado, true)) && posicion_barco[barco_buscado] == "?")
                            {
                                numero_posicion = parseInt(Math.random() * 2);
                                posicion_barco_buscado = (posicion_barco_buscado == "horizontal") ? "vertical" : "horizontal";
                                mejor_celda = cruz_barco[barco_buscado][posicion_barco_buscado][numero_posicion]; //Se escoge la celda superior o la de la izquierda.
                                if (mejor_celda == -1 || tablero_especulacion[mejor_celda] != -1 || !se_puede_poner_barco_especulando(mejor_celda, barco_buscado, posicion_barco_buscado, true)) { numero_posicion = (numero_posicion == 0) ? 1 : 0; mejor_celda = cruz_barco[barco_buscado][posicion_barco_buscado][numero_posicion]; } //Si no se puede o ya se ha bombardeado, se escoge la inferior o la de la derecha.
                            } 
                            //Define el barco elegido como el buscado, siempre que se haya encontrado una celda valida en la cruz:
                            if (mejor_celda == -1 || tablero_especulacion[mejor_celda] != -1 || !se_puede_poner_barco_especulando(mejor_celda, barco_buscado, posicion_barco_buscado, true)) { barco_elegido == -1; } else { barco_elegido = barco_buscado; }
                        }
                    }

                    //Si no sabe que barco buscar o antes ha tocado no buscar el barco antes alcanzado:
                    if (!sabe_que_barco_buscar || sabe_que_barco_buscar && se_hace < probabilidad || barco_elegido == -1)
                    {
//mostrar_mensaje("buscando: " + barco_elegido);
                        //Elige un barco entre los no hundidos:
                        var barco_elegido = 4; //Comienza por 4 porque el 0 es el 1.
                        do
                        {
                            if (dificultad <= 0) { barco_elegido = parseInt(Math.random() * 5); } //Si la dificultad es facil, lo escoge aleatoriamente.
                            else if (barcos["usuario"][barco_elegido] <= 0) { barco_elegido--; } //...pero si la dificultad es normal o dificil, escoge el mas grande disponible.
                        } while (barcos["usuario"][barco_elegido] <= 0); //Mientras el barco escogido ya se haya hundido, busca otro.


                        //mostrar_mensaje("buscando: " + barco_elegido);
                        //Escoge la casilla a bombardear:
                        if (dificultad <= 0) { dejamos_rebombardear = 5; se_hace = 100; }
                        else if (dificultad == 1) { dejamos_rebombardear = 2; se_hace = 60; }
                        else { dejamos_rebombardear = 0; se_hace = 35; }
                        probabilidad_rebombardear = parseInt(Math.random() * 100) + 1;
                        probabilidad = parseInt(Math.random() * 100) + 1;
                        do
                        {
                            if (se_hace >= probabilidad || ultima_celda_bombardeada == -1) { mejor_celda = parseInt(Math.random() * tablero_ancho * tablero_alto); } //Si la dificultad es facil o es la primera vez, la escoge aleatoriamente.
                            else { mejor_celda = bombardear_en_rejilla(); } //...pero si no, escoge una celda en forma de rejilla.
                            //alert("Celda: "+mejor_celda+"\ncontenido:"+tablero_especulacion[mejor_celda]);
                            //if (mejor_celda >= 93 && mejor_celda <= 99) { if (tablero_especulacion[mejor_celda] != -1) { alert("NO PUEDO ELEGIR " + mejor_celda + " porke kontiene: "+tablero_especulacion[mejor_celda]); } else { alert("SE ELIGEEEEEEEEEEEEEEEEE " + mejor_celda); } }
                        } while (tablero_especulacion[mejor_celda] != -1 && (!permitir_bombardear_lo_bombardeado || dejamos_rebombardear < probabilidad_rebombardear)); //Mientras la celda escogida haya sido bombardeada y no se permita rebombardear o si se permite rebombardear pero la probabilidad nos ha dicho que no.
                    }
                    
                    //Tener en cuenta si el barco elegido cabe en la celda escogida (usa mapa generado):
                    if (dificultad <= 0) { se_hace = 25; }
                    else if (dificultad == 1) { se_hace = 80; }
                    else { se_hace = 100; }
                    probabilidad = parseInt(Math.random() * 100) + 1;
                    if (se_hace >= probabilidad)
                    {
                        //Al mirar si el barco cabe, tener en cuenta las adyacentes:
                        if (dificultad <= 0) { se_hace = 0; }
                        else if (dificultad == 1) { se_hace = 60; }
                        else { se_hace = 100; }
                        probabilidad = parseInt(Math.random() * 100) + 1;
                        var mirar_adyacentes = false;
                        if (se_hace >= probabilidad) { mirar_adyacentes = true; }
                        //Se calcula la posicion del barco:
                        posicion_barco_buscado = posicion_barco[barco_elegido];
                        //Si no sabe en que posicion esta el barco, lo calcula aleatoriamente:
                        if (posicion_barco_buscado == "?") { posicion_barco_buscado = (parseInt(Math.random() * 2) == 1) ? "vertical" : "horizontal"; }
                        //Se mira si cabe o no:
                        var se_puede_poner = se_puede_poner_barco_especulando(mejor_celda, barco_elegido, posicion_barco_buscado, mirar_adyacentes);
                        //Si no se puede poner, mira si cabe rotando el barco:
                        if (!se_puede_poner)
                        {
                            //Se rota el barco:
                            posicion_barco_buscado = (posicion_barco_buscado == "horizontal") ? "vertical" : "horizontal";
                            //Se mira si cabe o no una vez rotado:
                            se_puede_poner = se_puede_poner_barco_especulando(mejor_celda, barco_elegido, posicion_barco_buscado, mirar_adyacentes);
                        }
                        if (!se_puede_poner) { mejor_celda = -1; }
                    }
                    
                    //Retorna la mejor celda a bombardear:
                    if (mejor_celda != -1)
                    {
                        ultima_celda_bombardeada = mejor_celda;
                        if (tablero_usuario[mejor_celda] == "0") { tablero_especulacion[mejor_celda] = "B"; }
                        else if (tablero_especulacion[mejor_celda] == -1) //Solo si la celda no ha sido antes bombardeada.
                        {
                            tablero_especulacion[mejor_celda] = tablero_usuario[mejor_celda];
                            if (tablero_usuario[mejor_celda] != "X")
                            {
                                var barco_alcanzado = tablero_usuario[mejor_celda] - 1;
                                //Si el barco elegido ha sido alcanzado anteriormente y esta vez tambien se ha acertado y se conoce una celda donde tambien esta este barco, se calcula su posicion (vertical u horizontal);
                                if (barcos["usuario"][barco_elegido] < barco_elegido+1 && barco_alcanzado == barco_elegido && ultima_celda_conocida_barco[barco_elegido] != -1)
                                {
                                    var fila_anterior = Math.floor(ultima_celda_conocida_barco[barco_elegido] / tablero_ancho);
                                    var columna_anterior = ultima_celda_conocida_barco[barco_elegido] % tablero_ancho;
                                    var fila_actual = Math.floor(mejor_celda / tablero_ancho);
                                    var columna_actual = mejor_celda % tablero_ancho;
                                    //Si la columna anterior es la misma que la actual, el barco esta en vertical:
                                    if (columna_anterior == columna_actual) { posicion_barco[barco_elegido] = "vertical"; }
                                    //...pero si no, esta en horizontal:
                                    else { posicion_barco[barco_elegido] = "horizontal"; }
                                }

                                var fila_barco_izquierda = Math.floor((mejor_celda - 1) / tablero_ancho);
                                var fila_barco_derecha = Math.floor((mejor_celda + 1) / tablero_ancho);
                                var columna_barco_superior = (mejor_celda - tablero_ancho) % tablero_ancho;
                                var columna_barco_inferior = (mejor_celda + tablero_ancho) % tablero_ancho;
                                var fila_barco_actual = Math.floor(mejor_celda / tablero_ancho);
                                var columna_barco_actual = mejor_celda % tablero_ancho;
                                //Define la cruz para el barco alcanzado, si no sabe si esta en vertical o en horizontal:
                                if (posicion_barco[barco_alcanzado] == "?")
                                {
                                //alert("A: " + posicion_barco[barco_alcanzado] + " ("+barco_alcanzado+")");
                                //alert("F: "+fila_barco_actual+" C: "+columna_barco_actual + ", CS: "+ columna_barco_superior +" CI: "+columna_barco_inferior+", FI: "+fila_barco_izquierda+" FD: "+fila_barco_derecha);
                                    if (columna_barco_actual == columna_barco_superior && columna_barco_superior >= 0 && columna_barco_superior < tablero_ancho) { if (mejor_celda - tablero_ancho >= 0 && tablero_especulacion[mejor_celda-tablero_ancho] == -1) { cruz_barco[barco_alcanzado]["vertical"][0] = mejor_celda - tablero_ancho; } else { cruz_barco[barco_alcanzado]["vertical"][0] = -1; } }
                                    if (columna_barco_actual == columna_barco_inferior && columna_barco_inferior >= 0 && columna_barco_inferior < tablero_ancho) { if (mejor_celda + tablero_ancho < tablero_ancho*tablero_alto && tablero_especulacion[mejor_celda+tablero_ancho] == -1) { cruz_barco[barco_alcanzado]["vertical"][1] = mejor_celda + tablero_ancho; } else { cruz_barco[barco_alcanzado]["vertical"][1] = -1; } }
                                    if (fila_barco_actual == fila_barco_izquierda && fila_barco_izquierda >= 0 && fila_barco_izquierda < tablero_alto) { if (mejor_celda - 1 >= 0 && tablero_especulacion[mejor_celda-1] == -1) { cruz_barco[barco_alcanzado]["horizontal"][0] = mejor_celda - 1; } else { cruz_barco[barco_alcanzado]["horizontal"][0] = -1; } }
                                    if (fila_barco_actual == fila_barco_derecha && fila_barco_derecha >= 0 && fila_barco_derecha < tablero_alto) { if (mejor_celda + 1 < tablero_ancho*tablero_alto && tablero_especulacion[mejor_celda+1] == -1) { cruz_barco[barco_alcanzado]["horizontal"][1] = mejor_celda + 1; } else { cruz_barco[barco_alcanzado]["horizontal"][1] = -1; } }
//alert("se ke barko buskar i es el " + barco_alcanzado + "("+cruz_barco[barco_alcanzado]["vertical"][0]+", "+cruz_barco[barco_alcanzado]["vertical"][1]+", "+cruz_barco[barco_alcanzado]["horizontal"][0]+", "+cruz_barco[barco_alcanzado]["horizontal"][1]+")");
                                }
                                //...pero si sabe en que posicion esta, define solo los extremos necesarios cruz:
                                else
                                {
                                //alert("B: " + posicion_barco[barco_alcanzado] + " ("+barco_alcanzado+")");
                                //alert("F: "+fila_barco_actual+" C: "+columna_barco_actual + ", CS: "+ columna_barco_superior +" CI: "+columna_barco_inferior+", FI: "+fila_barco_izquierda+" FD: "+fila_barco_derecha);
                                    if (posicion_barco[barco_alcanzado] == "horizontal")
                                    {
                                        if (fila_barco_actual == fila_barco_izquierda && fila_barco_izquierda >= 0 && fila_barco_izquierda < tablero_alto && tablero_especulacion[cruz_barco[barco_alcanzado]["horizontal"][0]] != -1) { if (mejor_celda - 1 >= 0 && tablero_especulacion[mejor_celda-1] == -1) { cruz_barco[barco_alcanzado]["horizontal"][0] = mejor_celda - 1; } else { cruz_barco[barco_alcanzado]["horizontal"][0] = -1; } }
                                        if (fila_barco_actual == fila_barco_derecha && fila_barco_derecha >= 0 && fila_barco_derecha < tablero_alto && tablero_especulacion[cruz_barco[barco_alcanzado]["horizontal"][1]] != -1) { if (mejor_celda + 1 < tablero_ancho*tablero_alto && tablero_especulacion[mejor_celda+1] == -1) { cruz_barco[barco_alcanzado]["horizontal"][1] = mejor_celda + 1; } else { cruz_barco[barco_alcanzado]["horizontal"][1] = -1; } }
                                    }
                                    else
                                    {
                                        if (columna_barco_actual == columna_barco_superior && columna_barco_superior >= 0 && columna_barco_superior < tablero_ancho && tablero_especulacion[cruz_barco[barco_alcanzado]["vertical"][0]] != -1) { if (mejor_celda - tablero_ancho >= 0 && tablero_especulacion[mejor_celda-tablero_ancho] == -1) { cruz_barco[barco_alcanzado]["vertical"][0] = mejor_celda - tablero_ancho; } else { cruz_barco[barco_alcanzado]["vertical"][0] = -1; } }
                                        if (columna_barco_actual == columna_barco_inferior && columna_barco_inferior >= 0 && columna_barco_inferior < tablero_ancho && tablero_especulacion[cruz_barco[barco_alcanzado]["vertical"][1]] != -1) { if (mejor_celda + tablero_ancho < tablero_ancho*tablero_alto && tablero_especulacion[mejor_celda+tablero_ancho] == -1) { cruz_barco[barco_alcanzado]["vertical"][1] = mejor_celda + tablero_ancho; } else { cruz_barco[barco_alcanzado]["vertical"][1] = -1; } }
                                    }
                                }
                                
                                //Se define el ultimo barco alcanzado y su ultima celda conocida:
                                //ultimo_barco_alcanzado = tablero_usuario[mejor_celda] - 1;
                                //ultima_celda_conocida_barco[ultimo_barco_alcanzado] = mejor_celda;
                                ultima_celda_conocida_barco[tablero_usuario[mejor_celda] - 1] = mejor_celda;
                                //Si el barco ya se va a hundir, ya no se busca el barco:
                                //if (barcos["usuario"][barco_elegido] == 1) { sabe_que_barco_buscar = false; }
                            }
                        }
                    }
                    else
                    {
                        mejor_celda = calcular_mejor_posicion();
                        //if (mejor_celda != -1) { alert("SOLUCIONADO!!!!!!!!!! (problema al buscar " + barco_elegido + " en " + posicion_barco_buscado + "). se pondra en "+mejor_celda); }
                    }
                    return mejor_celda;
                }
                
                
                //Funcion que devuelve la siguiente celda en rejilla:
                function bombardear_en_rejilla()
                {
                    var celda_escogida = -1;

                    //Si no se ha comenzado a hacer rejilla, escoge una celda aleatoria:
                    if (ultima_celda_bombardeada == -1) { celda_escogida = parseInt(Math.random() * tablero_ancho * tablero_alto); }
                    //...pero si ya se ha comenzado, escoge la ultima celda bombardeada y le suma dos:
                    else
                    {
                        //Calcula que barco buscar:
                        var barco_buscado = 0;
                        for (var x = 4; x >= 0; x--)
                        {
                            //Si el barco ha sido o no tocado pero aun no ha sido hundido, se escoge para ser buscado y sale del bucle:
                            if (barcos["usuario"][x] <= x+1 && barcos["usuario"][x] > 0) { barco_buscado = x; break; }
                        }
                        celda_escogida = ultima_celda_bombardeada + barco_buscado;
                        var fila_ultima_celda_bombardeada = Math.floor(ultima_celda_bombardeada / tablero_ancho) + 1;
                        var fila_celda_escogida = Math.floor(celda_escogida / tablero_ancho) + 1;
                        if (fila_ultima_celda_bombardeada != fila_celda_escogida) { celda_escogida++; }
                    }
                    
                    do
                    {
                        //Si la celda ya habia sido bombardeada, se escoge la siguiente:
                        if (tablero_especulacion[celda_escogida] != -1) { celda_escogida++; }
                        //Si la celda se pasa del alto y ancho del mapa, se torna a cero:
                        if (celda_escogida >= tablero_ancho * tablero_alto) { celda_escogida = 0; }
                    } while (tablero_especulacion[celda_escogida] != -1); //Se va sumando 1 a la celda escogida siempre que a celda escogida ya haya sido bombardeada.
                    
                    return celda_escogida;
                }
                
                
                //Funcion que bombardea la celda elegida:
                function bombardear(numero_celda, atacante)
                {
                    //Define si se ha hundido algun barco o no:
                    var se_ha_hundido_alguno = false;
                    
                    //Si el juego esta bloqueado, sale de la funcion:
                    if (juego_bloqueado) { return; }
                    
                    //Si esta el menu de opciones a la vista y el atacante es el usuario, sale de la funcion:
                    if (atacante == "usuario" && document.getElementById("menu_opciones").style.visibility == "visible") { return; }
                    //alert("bombardea el "+atacante);
                    //Si no ha comenzado el juego, sale de la funcion:
                    if (!se_ha_comenzado) { return; }
                    
                    //Si al atacante no le corresponde el turno, sale de la funcion:
                    if (atacante != jugador_actual) { return; }
                    
                    //Guarda la celda que se ha escogido atacar:
                    var contenido_celda = (atacante == "ordenador") ? tablero_usuario[numero_celda] : tablero_ordenador[numero_celda];
                    var contenido_celda_anterior = contenido_celda;
                    
                    //Guarda el oponente:
                    var oponente = (atacante == "ordenador") ? "usuario" : "ordenador";
                    
                    //Si se puede rebombardear o si no se puede pero la celda no ha sido bombardeada:
                    if (permitir_bombardear_lo_bombardeado || contenido_celda != "B" && contenido_celda != "X")
                    {
                        //Borra la celda marcada del tablero del oponente:
                        document.getElementById("celda_" + numero_celda + "_" + oponente).style.background = "#6600ff";

                        //Si hay agua, marca las matrices como agua bombardeada:
                        if (contenido_celda == 0) { contenido_celda = "B"; }
                        //...pero si hay un barco:
                        else if (contenido_celda != "B" && contenido_celda != "X")
                        {
                            //Resta una casilla al barco tocado:
                            barcos[oponente][contenido_celda-1]--;
                            //Muestra una explosion:
                            document.getElementById("celda_" + numero_celda + "_" + oponente + "_invisible").innerHTML = '<img src="img/boom.gif" style="width:' + celda_ancho + 'px; height:' + celda_alto + 'px; filter:alpha(opacity=60); opacity:0.6; -moz-opacity:0.6; -khtml-opacity:0.6; z-index:3;">';
                            //Si el barco tocado ya se ha hundido (FALTAAAAAAA!!!!!):
                            if (barcos[oponente][contenido_celda-1] == 0)
                            {
                                //Si el afectado es el usuario, muestra el mensaje de tocado y hundido:
                                if (oponente == "usuario")
                                {
                                    se_ha_hundido_alguno = true;
                                    mostrar_mensaje("Tu barco " + contenido_celda + " ha sido tocado y hundido.");
                                    if (alerts_activados) { alert("Tu barco " + contenido_celda + " ha sido tocado y hundido."); mostrar_mensaje(""); }
                                }
                                //...pero si es el ordenador, tambien informa:
                                else
                                {
                                    se_ha_hundido_alguno = true;
                                    document.getElementById("bomba").style.visibility = "hidden";
                                    mostrar_mensaje("El barco " + contenido_celda + " del ordenador ha sido tocado y hundido.");
                                    document.getElementById("barcos").innerHTML += " [" + contenido_celda + "]";
                                    if (alerts_activados) { alert("El barco " + contenido_celda + " del ordenador ha sido tocado y hundido."); mostrar_mensaje(""); }
                                }
                                //Desmarca las coordenadas:
                                //document.getElementById("y_" + numero_fila_invertida).style.background = "#000011";
                                //document.getElementById("x_" + numero_columna + "_" + oponente).style.background = "#000011";
                                marcar_coordenada(-1, oponente);
                                //Deja de mostrar el mensaje:
                                setTimeout('mostrar_mensaje("");', 2000);
                            } //FALTA: ACABAR!!!
                            //else { mostrar_mensaje("Tocado"); setTimeout("mostrar_mensaje('');", 500); }
                            //Marca el barco como bombardeado:
                            contenido_celda = "X";
                        }
                        //Se pone el contenido nuevo al tablero correspondiente:
                        if (atacante == "usuario") { tablero_ordenador[numero_celda] = contenido_celda; }
                        else { tablero_usuario[numero_celda] = contenido_celda; }
                        //Si esta la opcion de marcar lo ya bombardeado y habia agua, lo marca:
                        if (marcar_bombardeado && contenido_celda == "B")
                        {
                            //if (atacante == "usuario") { document.getElementById("celda_" + numero_celda + "_ordenador_invisible").innerHTML = '<img src="img/x.gif" style="width:' + celda_ancho + 'px; height:' + celda_alto + 'px;">'; }
                            //else { document.getElementById("celda_" + numero_celda + "_usuario_invisible").innerHTML = '<img src="img/x.gif" style="width:' + celda_ancho + 'px; height:' + celda_alto + 'px;">'; }
                            //Si hay agua, pone una X:
                            if (contenido_celda == "B") { document.getElementById("celda_" + numero_celda + "_" + oponente + "_invisible").innerHTML = '<img src="img/x.gif" style="width:' + celda_ancho + 'px; height:' + celda_alto + 'px; filter:alpha(opacity=60); opacity:0.6; -moz-opacity:0.6; -khtml-opacity:0.6; z-index:3;">'; }
                            //...pero si no, es que habia un barco y pone una explosion:
                                //document.getElementById("celda_" + numero_celda + "_" + oponente).innerHTML = '<img src="img/boom.gif" style="width:' + celda_ancho + 'px; height:' + celda_alto + 'px; z-index:3;">';
                                //document.getElementById("celda_" + numero_celda + "_" + oponente).style.zIndex = 3;
                        }
                        //Otorga el turno a su contrario:
                        if (alerts_activados || !se_ha_hundido_alguno) { mostrar_mensaje(""); otorgar_control(oponente); }
                        else
                        {
                            juego_bloqueado = true;
                            setTimeout("juego_bloqueado = false; mostrar_mensaje(''); otorgar_control('"+oponente+"');", 2010);
                        }
                    }
                    //...pero si no, vuelve a otorgar el control al mismo jugador:
                    else { otorgar_control(atacante); }
                }
                

                //Funcion que calcula si se ha terminado el juego y quien es el ganador:
                function calcular_fin_de_juego(ultimo_atacante)
                {
                    //Calcula si el ultimo atacante ha conseguido bombardear todos los barcos de su oponente:
                    var oponente = (ultimo_atacante == "ordenador") ? "usuario" : "ordenador";
                    var quedan_barcos = false;
                    for (var x = 0; x < 5; x++) { if (barcos[oponente][x] > 0) { quedan_barcos = true; } }

                    //Si ya no quedan barcos, finaliza el juego:
                    if (!quedan_barcos)
                    {
                        //Oculta la bomba (por si estaba visible):
                        document.getElementById("bomba").style.visibility = "hidden";
                        //Notifica que ha acabado el juego e informa del ganador:
                        if (oponente == "usuario")
                        {
                            mostrar_mensaje("Fin del juego. Has perdido.");
                            document.getElementById("reloj").style.visibility = "hidden";
                            if (alerts_activados) { alert("Fin del juego. Has perdido."); }
                            else { mostrar_alerta("Fin del juego. Has perdido.", true); }
                        }
                        else
                        {
                            mostrar_mensaje("Fin del juego. Enhorabuena, has ganado.");
                            document.getElementById("reloj").style.visibility = "hidden";
                            if (alerts_activados) { alert("Fin del juego. Enhorabuena, has ganado."); }
                            else { mostrar_alerta("Fin del juego. Enhorabuena, has ganado.", true); }
                        }
                        if (alerts_activados)
                        {
                            //Se muestra el mensaje de espera:
                            mostrar_mensaje("Cargando...");
                            //Despues de un instante, comienza el juego de nuevo y se quita el mensaje de espera:
                            setTimeout("iniciar_juego(true); mostrar_mensaje('');", 10);
                        }
                        return true;
                    }
                    //...pero si aun quedan, continua:
                    else { return false; }
                }
                

                //Funcion que inicia el juego por primera vez:
                function iniciar_juego_primera_vez()
                {
                    //Se muestra el mensaje de espera:
                    mostrar_mensaje("Inicializando...");
                    
                    //Se ponen las opciones en el menu de opciones:
                    document.getElementById("tablero_ancho").value = tablero_ancho;
                    document.getElementById("tablero_alto").value = tablero_alto;
                    document.getElementById("permitir_bombardear_lo_bombardeado").checked = permitir_bombardear_lo_bombardeado;
                    document.getElementById("permitir_poner_barcos_adyacentes").checked = permitir_poner_barcos_adyacentes;
                    document.getElementById("marcar_bombardeado").checked = marcar_bombardeado;
                    var numero_primer_jugador = (primer_jugador == "usuario") ? 0 : 1;
                    document.getElementById("primer_jugador").options[numero_primer_jugador].selected = true;
                    document.getElementById("dificultad").options[dificultad].selected = true;
                    
                    //Se muestra el mensaje de espera:
                    setTimeout('mostrar_mensaje("Cargando...");', 10)

                    //Se inicia el juego:
                    setTimeout("iniciar_juego(false);", 50); //Se inicia el juego despues de unos milisegundos para dar dar tiempo a mostrar el mensaje de espera.
                }


                //Funcion que comienza un juevo nuevo:
                function iniciar_juego(mismos_tableros)
                {
                    //Se define como que el juego no esta bloqueado:
                    juego_bloqueado = false;

                    //Se restauran los valores de las variables:


                    se_ha_comenzado = false;
                    ultima_celda_indicada = -1;
                    document.getElementById("barcos").innerHTML = "";

                    //Se restauran las variables de la inteligencia artificial del ordenador:
                    for (var x = 0; x < 5; x++)
                    {
                        ultima_celda_conocida_barco[x] = cruz_barco[x]["vertical"][0] = cruz_barco[x]["horizontal"][0] = cruz_barco[x]["vertical"][1] = cruz_barco[x]["horizontal"][1] = -1;
                        posicion_barco[x] = "?";
                    }
                    for (x = 0; x < tablero_ancho * tablero_alto; x++) { tablero_especulacion[x] = -1; }
                    sabe_que_barco_buscar = false;
                    //barco_buscado = ultima_celda_bombardeada = ultimo_barco_alcanzado = -1;
                    barco_buscado = ultima_celda_bombardeada = -1;

                    //Define que ningun barco se esta arrastrando:
                    var barco_arrastrandose = 0;

                    //Define que ningun barco se ha puesto aun en el tablero y vuelve a ponerle el numero de celdas correspondiente para cada jugador:
                    for (x = 0; x < 5; x++) { barcos_puestos_primera_celda[x] = -1; barcos_puestos[x] = false; barcos["usuario"][x] = barcos["ordenador"][x] = x+1; }
                    
                    //Si se ha seteado adaptar las celdas segun el tama�o del mapa, se adapta:
                    if (adaptar_celdas)
                    {
                        //Se redimensionan el ancho y alto de las celdas segun sean el alto y ancho de los tableros:
                        if (tablero_ancho <= 12 && tablero_alto <= 12) { celda_ancho = celda_ancho_predeterminado; celda_alto = celda_alto_predeterminado; }
                        else if (tablero_ancho <= 20 && tablero_alto <= 20) { celda_ancho = parseInt(celda_ancho_predeterminado - celda_ancho_predeterminado * 0.25); celda_alto = parseInt(celda_alto_predeterminado - celda_alto_predeterminado * 0.25); }
                        else if (tablero_ancho <= 30 && tablero_alto <= 30) { celda_ancho = parseInt(celda_ancho_predeterminado / 2); celda_alto = parseInt(celda_alto_predeterminado / 2); }
                        else if (tablero_ancho <= 40 && tablero_alto <= 40) { celda_ancho = parseInt(celda_ancho_predeterminado / 2 - celda_ancho_predeterminado * 0.15); celda_alto = parseInt(celda_alto_predeterminado / 2 - celda_alto_predeterminado * 0.15); }
                        else if (tablero_ancho <= 50 && tablero_alto <= 50) { celda_ancho = parseInt(celda_ancho_predeterminado * 0.25); celda_alto = parseInt(celda_alto_predeterminado * 0.25); }
                        else { celda_ancho = parseInt(celda_ancho_predeterminado * 0.15); celda_alto = parseInt(celda_alto_predeterminado * 0.15); }
                    } else { celda_ancho = celda_ancho_predeterminado; celda_alto = celda_alto_predeterminado; } //...y si no, se setea el alto y ancho predeterminado para las celdas.

                    //Si el espacio entre celdas es demasiado como para hacer quedar mal colocados los barcos, se pone a su maximo permitido:
                    if (espacio_entre_celdas * 4 > celda_ancho) { espacio_entre_celdas = celda_ancho / 4; }
                    if (espacio_entre_celdas * 4 > celda_alto) { espacio_entre_celdas = celda_alto / 4; }

                    //Matriz que contiene las ultimas celdas marcadas, para poder desmarcarlas luego (el indice 0 tiene -1 si no hay celdas marcadas anteriormente):
                    celdas_marcadas = new Array(tablero_ancho*tablero_alto);
                    for (x = 0; x < celdas_marcadas.length; x++) { celdas_marcadas[x] = -1; }

                    //Se crean los tableros (vacios):
                    crear_tableros(mismos_tableros);
                   
                    //Se deja de mostrar el mensaje de espera:
                    mostrar_mensaje("");
                }

                
                //Funcion que crea los tableros (vacios):
                function crear_tableros(mismos_tableros)
                {
                    //Se vacian las matrices de ambos tableros:
                    tablero_usuario = new Array(tablero_ancho*tablero_alto);
                    tablero_ordenador = new Array(tablero_ancho*tablero_alto);
                    for (var x = 0; x < tablero_usuario.length; x++) { tablero_usuario[x] = tablero_ordenador[x] = 0; }
                    
                    //Dibujamos el mapa vacio:
                    var mapa_vacio_html_usuario = ""; //Guarda el codigo HTML de la zona de juego del usuario.
                    var mapa_vacio_html_ordenador = ""; //Guarda el codigo HTML de la zona de juego del ordenador.
                    var mapa_vacio_html_invisible_usuario = ""; //Guarda el codigo HTML de la zona de juego invisible del usuario.
                    var mapa_vacio_html_invisible_ordenador = ""; //Guarda el codigo HTML de la zona de juego invisible del ordenador.
                    var coordenadas_x_html_usuario = ""; //Guarda el codigo HTML del eje de coordenadas de las X del usuario.
                    var coordenadas_x_html_ordenador = ""; //Guarda el codigo HTML del eje de coordenadas de las X del ordenador.
                    var coordenadas_y_html = ""; //Guarda el codigo HTML del eje de coordenadas de las Y.
                    var celda_pos_x = 0; //La posicion horizontal de cada celda.
                    var celda_pos_y = 0; //La posicion vertical de cada celda.
                    var contador_columna = 0; //Numero de columna en la que se encuentra el bucle.
                    var contador_fila = 0; //Numero de fila en la que se encuentra el bucle.
                    var coordenada_y_invertida = 0;
                    var coordenada_y_pos_y = 0;
                    var numero_a_mostrar;
                    for (x = 0; x < tablero_ancho * tablero_alto && !mismos_tableros; x++)
                    {
                        //Si se ha escogido mostrar numeros en las casillas, el numero a mostrar es el de la casilla y si no estara vacio:
                        numero_a_mostrar = (mostrar_numero_celdas) ? x : "";
                        //Define la posicion horizontal y vertical de la celda actual:
                        celda_pos_x = (contador_columna * celda_ancho) + (espacio_entre_celdas * contador_columna);
                        celda_pos_y = (contador_fila * celda_alto) + (espacio_entre_celdas * contador_fila);
                        //Crea el codigo HTML correspondiente a la celda actual:
                        mapa_vacio_html_usuario += '<div id="celda_' + x + '_usuario" style="position:absolute; left:' + celda_pos_x + 'px; top:' + celda_pos_y + 'px; width:' + celda_ancho + 'px; height:' + celda_alto + 'px; color:#dddddd; background:#6600ff; text-align:center; font-size:' + parseInt(celda_alto / 4) + 'px; line-height:' + celda_alto + 'px; filter:alpha(opacity=30); opacity:0.3; -moz-opacity:0.3; -khtml-opacity:0.3; cursor:default; z-index:1;" title="( ' + contador_columna + ', ' + eval(tablero_alto - contador_fila - 1) + ' )"></div>';
                        mapa_vacio_html_ordenador += '<div id="celda_' + x + '_ordenador" style="position:absolute; left:' + celda_pos_x + 'px; top:' + celda_pos_y + 'px; width:' + celda_ancho + 'px; height:' + celda_alto + 'px; color:#dddddd; background:#6600ff; text-align:center; font-size:' + parseInt(celda_alto / 4) + 'px; line-height:' + celda_alto + 'px; filter:alpha(opacity=30); opacity:0.3; -moz-opacity:0.3; -khtml-opacity:0.3; cursor:default; z-index:1;" title="( ' + contador_columna + ', ' + eval(tablero_alto - contador_fila - 1) + ' )"></div>';
                        mapa_vacio_html_invisible_usuario += '<div id="celda_' + x + '_usuario_invisible" style="position:absolute; left:' + celda_pos_x + 'px; top:' + celda_pos_y + 'px; width:' + celda_ancho + 'px; height:' + celda_alto + 'px; color:#ffffff; background:transparent; text-align:center; font-size:' + parseInt(celda_alto / 4) + 'px; line-height:' + celda_alto + 'px; cursor:pointer; cursor:hand; z-index:4;" onMouseOver="marcar_coordenada(' + x + ', \'usuario\');" onMouseOut="marcar_coordenada(-1, \'usuario\'); document.getElementById(\'celda_' + x + '_usuario\').style.background = \'#6600ff\';" onClick="poner_barco(' + x + ');" title="( ' + contador_columna + ', ' + eval(tablero_alto - contador_fila - 1) + ' )"><img src="img/invisible.gif" alt="" style="position:absolute; left:0px; top:0px; width:' + celda_ancho + 'px; height:' + celda_alto + 'px;" title="( ' + contador_columna + ', ' + eval(tablero_alto - contador_fila - 1) + ' )">' + numero_a_mostrar + '</div>';
                        mapa_vacio_html_invisible_ordenador += '<div id="celda_' + x + '_ordenador_invisible" style="position:absolute; left:' + celda_pos_x + 'px; top:' + celda_pos_y + 'px; width:' + celda_ancho + 'px; height:' + celda_alto + 'px; color:#ffffff; background:transparent; text-align:center; font-size:' + parseInt(celda_alto / 4) + 'px; line-height:' + celda_alto + 'px; cursor:pointer; cursor:hand; z-index:4;" onMouseOver="if (barco_arrastrandose == 0) { marcar_coordenada(' + x + ', \'ordenador\'); }" onMouseOut="marcar_coordenada(-1, \'ordenador\'); document.getElementById(\'celda_' + x + '_ordenador\').style.background = \'#6600ff\';" onClick="bombardear(' + x + ', \'usuario\');" title="( ' + contador_columna + ', ' + eval(tablero_alto - contador_fila - 1) + ' )"><img src="img/invisible.gif" alt="" style="position:absolute; left:0px; top:0px; width:' + celda_ancho + 'px; height:' + celda_alto + 'px;" title="( ' + contador_columna + ', ' + eval(tablero_alto - contador_fila - 1) + ' )">' + numero_a_mostrar + '</div>';
                        //Si estamos en la primera fila, se dibuja una coordenada mas en el eje de las X (poniendo el numero de columna):
                        if (contador_fila == 0)
                        {
                            coordenadas_x_html_usuario += '<div id="x_' + contador_columna + '_usuario" style="position:absolute; left:' + celda_pos_x + 'px; top:0px; width:' + celda_ancho + 'px; height:' + celda_alto + 'px; color:#ffffff; background:#000011; text-align:center; line-height:' + celda_alto + 'px; font-size:' + parseInt(celda_alto/2) + 'px; cursor:default;" title="X = ' + contador_columna +  '">' + contador_columna + '</div>';
                            coordenadas_x_html_ordenador += '<div id="x_' + contador_columna + '_ordenador" style="position:absolute; left:' + celda_pos_x + 'px; top:0px; width:' + celda_ancho + 'px; height:' + celda_alto + 'px; color:#ffffff; background:#000011; text-align:center; line-height:' + celda_alto + 'px; font-size:' + parseInt(celda_alto/2) + 'px; cursor:default;" title="X = ' + contador_columna + '">' + contador_columna + '</div>';
                        }
                        //Si estamos en la primera fila, se dibuja una coordenada mas en el eje de las Y (poniendo el numero de fila):
                        if (contador_columna == 0)
                        {
                            coordenada_y_invertida = tablero_alto - contador_fila - 1;
                            coordenada_y_pos_y = (coordenada_y_invertida * celda_alto) + (espacio_entre_celdas * coordenada_y_invertida);
                            coordenadas_y_html += '<div id="y_' + contador_fila + '" style="position:absolute; left:0px; top:' + coordenada_y_pos_y + 'px; width:' + celda_ancho + 'px; height:' + celda_alto + 'px; color:#ffffff; background:#000011; text-align:center; line-height:' + celda_alto + 'px; font-size:' + parseInt(celda_alto/2) + 'px; cursor:default;" title="Y = ' + contador_fila + '">' + contador_fila + '</div>'; //Se pone de abajo a arriba.
                        }
                        //Incrementa una columna:
                        contador_columna++;
                        //Si se ha alcanzado el tope de columnas, se vuelve a la primera columna y se inrementa una fila:
                        if (contador_columna >= tablero_ancho) { contador_columna = 0; contador_fila++; }
                    }
                    //Se pone el ancho y alto correspondiente en las zonas de juego:
                    document.getElementById("zona_juego_usuario").style.width = document.getElementById("zona_juego_ordenador").style.width = (tablero_ancho * celda_ancho) + (tablero_ancho * espacio_entre_celdas) - espacio_entre_celdas + "px";
                    document.getElementById("zona_juego_usuario").style.height = document.getElementById("zona_juego_ordenador").style.height = (tablero_alto * celda_alto) + (tablero_alto * espacio_entre_celdas) - espacio_entre_celdas + "px";
                    //Se posicionan las zonas de juego en horizontal y en vertical:
                    document.getElementById("zona_juego_usuario").style.left = celda_ancho + 10 + espacio_entre_celdas * 2 + "px";
                    document.getElementById("zona_juego_ordenador").style.left = parseInt(document.getElementById("zona_juego_usuario").style.left) + parseInt(document.getElementById("zona_juego_usuario").style.width) + parseInt(celda_ancho / 2) + espacio_entre_celdas * 2 + "px";
                    document.getElementById("zona_juego_usuario").style.top = document.getElementById("zona_juego_ordenador").style.top = 30 + "px";
                    //Se pone el ancho, alto, posicion horizontal y vertical de las zonas de juego invisibles:
                    document.getElementById("zona_juego_usuario_invisible").style.left = document.getElementById("zona_juego_usuario").style.left;
                    document.getElementById("zona_juego_usuario_invisible").style.top = document.getElementById("zona_juego_usuario").style.top;
                    document.getElementById("zona_juego_ordenador_invisible").style.left = document.getElementById("zona_juego_ordenador").style.left;
                    document.getElementById("zona_juego_ordenador_invisible").style.top = document.getElementById("zona_juego_ordenador").style.top;
                    document.getElementById("zona_juego_usuario_invisible").style.width = document.getElementById("zona_juego_ordenador_invisible").style.width = document.getElementById("zona_juego_usuario").style.width;
                    document.getElementById("zona_juego_usuario_invisible").style.height = document.getElementById("zona_juego_ordenador_invisible").style.height = document.getElementById("zona_juego_usuario").style.height;
                    //Se posicionan vertical y horizontalmente los ejes de coordenadas:
                    document.getElementById("coordenadas_x_usuario").style.left = document.getElementById("zona_juego_usuario").style.left;
                    document.getElementById("coordenadas_x_ordenador").style.left = document.getElementById("zona_juego_ordenador").style.left;
                    document.getElementById("coordenadas_x_usuario").style.top = document.getElementById("coordenadas_x_ordenador").style.top = parseInt(document.getElementById("zona_juego_usuario").style.top) + parseInt(document.getElementById("zona_juego_usuario").style.height) + espacio_entre_celdas * 2 + "px";
                    document.getElementById("coordenadas_x_usuario").style.width = document.getElementById("coordenadas_x_ordenador").style.width = document.getElementById("zona_juego_usuario").style.width;
                    document.getElementById("coordenadas_y").style.width = celda_ancho + "px";
                    document.getElementById("coordenadas_y").style.height = document.getElementById("zona_juego_usuario").style.height;
                    document.getElementById("coordenadas_x_usuario").style.height = document.getElementById("coordenadas_x_ordenador").style.height = celda_alto + "px";
                    //Se posiciona vertical y horizontalmente y se pone el ancho correspondiente al div que contiene los barcos:
                    document.getElementById("barcos").style.top = parseInt(document.getElementById("coordenadas_x_usuario").style.top) + parseInt(document.getElementById("coordenadas_x_usuario").style.height) + 10 + "px";
                    document.getElementById("barcos").style.left = document.getElementById("zona_juego_usuario").style.left;
                    document.getElementById("barcos").style.width = (celda_ancho * 15) + (6 * espacio_entre_celdas) + "px";
                    document.getElementById("barcos").style.height = celda_alto + "px";
                    //Se establece el line-height correspondiente al div que contiene los barcos:
                    document.getElementById("barcos").style.lineHeight = celda_alto + "px";
                    //Se establece el font-size correspondiente al div que contiene los barcos:
                    document.getElementById("barcos").style.fontSize = parseInt(celda_alto / 2) + "px";
                    //Se alargan y agrandan convenientemente todos los div de cada barco y los tiles (div e img) que los componen:
                    for (x = 1; x <= 5; x++)
                    {
                        //Se posicionan verticalmente igual que el div de fondo de los barcos:
                        document.getElementById("barco_" + x).style.top = document.getElementById("barcos").style.top;
                        //Se agradan o reducen convenientemente el div que contiene el barco X:
                        document.getElementById("barco_" + x).style.width = (celda_ancho * x) + "px";
                        //Se alargan o reducen convenientemente el div que contiene el barco X:
                        document.getElementById("barco_" + x).style.height = celda_alto + "px";
                        //Se establece el line.height correspondiente al div que contiene el barco X:
                        document.getElementById("barco_" + x).style.lineHeight = celda_alto + "px";
                        //Se les pone el z-Index inicial correspondiente:
                        document.getElementById("barco_" + x).style.zIndex = document.getElementById("barco_" + x + "_vertical").style.zIndex = 2;
                        //Se posicional horizontalmente los div de cada barco:
                        if (x > 1) { document.getElementById("barco_" + x).style.left = parseInt(document.getElementById("barco_" + eval(x-1)).style.left) + parseInt(document.getElementById("barco_" + eval(x-1)).style.width) + espacio_entre_celdas + "px"; }
                        else { document.getElementById("barco_" + x).style.left = parseInt(document.getElementById("barcos").style.left) + espacio_entre_celdas + "px"; }
                        //Se posicionan los divs transparentes de cada barco igual que el visible, y se hacen invisibles:
                        document.getElementById("barco_" + x + "_invisible").style.left = document.getElementById("barco_" + x).style.left;
                        document.getElementById("barco_" + x + "_invisible").style.top = document.getElementById("barco_" + x).style.top;
                        document.getElementById("barco_" + x + "_invisible").style.width = document.getElementById("barco_" + x).style.width;
                        document.getElementById("barco_" + x + "_invisible").style.height = document.getElementById("barco_" + x).style.height;
                        document.getElementById("barco_" + x + "_invisible").style.visibility = "hidden";
                        //Se posicionan los divs de los barcos verticales y se hacen invisibles:
                        document.getElementById("barco_" + x + "_vertical").style.width = document.getElementById("barco_" + x).style.height; //El width del barco en vertical hace lo mismo que el height del horizontal.
                        document.getElementById("barco_" + x + "_vertical").style.height = document.getElementById("barco_" + x).style.width; //El height del barco en vertical hace lo mismo que el width del horizontal.
                        document.getElementById("barco_" + x + "_vertical").style.visibility = "hidden";
                        //Se hacen visibles los barcos horizontales:
                        document.getElementById("barco_" + x).style.visibility = "visible";
                        //Se pone el ancho y alto correspondiente para la imagen transparente que esta dentro del div del barco:
                        document.getElementById("barco_" + x + "_img_invisible").style.width = document.getElementById("barco_" + x).style.width;
                        document.getElementById("barco_" + x + "_img_invisible").style.height = document.getElementById("barco_" + x).style.height;
                        //Se recorren los tiles del barco X:
                        for (var y = 1; y <= x; y++)
                        {
                            document.getElementById("barco_" + x + "_" + y).style.left = (y - 1) * celda_ancho + "px"; //Se posiciona horizontalmente el div que contiene el tile Y del barco X.
                            document.getElementById("barco_" + x + "_" + y + "_vertical").style.top = document.getElementById("barco_" + x + "_" + y).style.left; //Cada celda del vertical tiene el mismo top que el left del horizontal.
                            document.getElementById("barco_" + x + "_" + y).style.width = document.getElementById("barco_" + x + "_" + y + "_vertical").style.width = document.getElementById("barco_" + x + "_" + y + "_img_vertical").style.width = document.getElementById("barco_" + x + "_" + y + "_img").style.width = celda_ancho + "px"; //Se setea el ancho correspondiente para el tile Y del barco X (tanto en el div como en el img que esta dentro).
                            document.getElementById("barco_" + x + "_" + y).style.height = document.getElementById("barco_" + x + "_" + y + "_vertical").style.height = document.getElementById("barco_" + x + "_" + y + "_img_vertical").style.height = document.getElementById("barco_" + x + "_" + y + "_img").style.height = celda_alto + "px"; //Se setea el alto correspondiente para el tile Y del barco X (tanto en el div como en el img que esta dentro).
                            document.getElementById("barco_" + x + "_" + y).style.lineHeight = celda_alto + "px";
                        }
                    }
                    //Se posiciona correctamente el div de rotar el barco:
                    document.getElementById("boton_rotar").style.top = document.getElementById("barcos").style.top;
                    document.getElementById("boton_rotar").style.left = parseInt(document.getElementById("barcos").style.left) + parseInt(document.getElementById("barcos").style.width) + (espacio_entre_celdas * 2) + "px";
                    document.getElementById("boton_rotar").style.width = document.getElementById("boton_rotar_img").style.width = celda_ancho + "px";
                    document.getElementById("boton_rotar").style.height = document.getElementById("boton_rotar_img").style.height = celda_alto + "px";
                    //Se hace lo conveniente con el div de la bomba y su imagen:
                    document.getElementById("bomba").style.width = document.getElementById("bomba_img").style.width = celda_ancho;
                    document.getElementById("bomba").style.height = document.getElementById("bomba_img").style.height = celda_alto;
                    document.getElementById("bomba").style.visibility = "hidden";
                    //Se pone el ancho y alto correspondiente al div del reloj:
                    document.getElementById("reloj").style.width = document.getElementById("reloj_img").style.width = celda_ancho;
                    document.getElementById("reloj").style.height = document.getElementById("reloj_img").style.height = celda_alto;
                    //Se posiciona el div con la informacion del juego, para que no estorbe:
                    document.getElementById("informacion").style.top = parseInt(document.getElementById("barcos").style.top) + parseInt(document.getElementById("barcos").style.height) + 10 + "px";
                    //Se pone el font size y el line height correspondiente en las coordenadas y en las zonas de juego (invisibles o no):                    
                    document.getElementById("coordenadas_x_usuario").style.fontSize = document.getElementById("coordenadas_x_ordenador").style.fontSize = document.getElementById("coordenadas_y").style.fontSize = parseInt(celda_alto / 2) + "px";
                    document.getElementById("coordenadas_x_usuario").style.lineHeight = document.getElementById("coordenadas_x_ordenador").style.lineHeight = document.getElementById("coordenadas_y").style.lineHeight = celda_alto + "px";
                    document.getElementById("zona_juego_usuario").style.fontSize = document.getElementById("zona_juego_ordenador").style.fontSize = document.getElementById("zona_juego_usuario_invisible").style.fontSize = document.getElementById("zona_juego_ordenador_invisible").style.fontSize = parseInt(celda_alto / 4) + "px";
                    document.getElementById("zona_juego_usuario").style.lineHeight = document.getElementById("zona_juego_ordenador").style.lineHeight = document.getElementById("zona_juego_usuario_invisible").style.lineHeight = document.getElementById("zona_juego_ordenador_invisible").style.lineHeight = document.getElementById("coordenadas_x_usuario").style.lineHeight;
                    //Se ponen bien los atributos al boton de comenzar batalla:
                    document.getElementById("boton_comenzar_juego").style.fontSize = parseInt(celda_alto / 2) + "px";
                    document.getElementById("boton_comenzar_juego").style.height = celda_alto - espacio_entre_celdas * 4 - 2 + "px";
                    document.getElementById("boton_comenzar_juego").style.width = (18 * parseInt(document.getElementById("boton_comenzar_juego").style.fontSize)) + "px"; //"Comenzar batalla" = (16 letras + 2 de espaciado) * fontsize.
                    document.getElementById("boton_comenzar_juego").style.lineHeight = document.getElementById("boton_comenzar_juego").style.height;
                    document.getElementById("boton_comenzar_juego").style.top = parseInt(document.getElementById("barcos").style.top) + parseInt((parseInt(document.getElementById("barcos").style.height) - parseInt(document.getElementById("boton_comenzar_juego").style.height)) / 2) - 1 + "px";
                    document.getElementById("boton_comenzar_juego").style.left = parseInt(document.getElementById("barcos").style.left)  + parseInt(parseInt(document.getElementById("barcos").style.width) / 2) - parseInt(parseInt(document.getElementById("boton_comenzar_juego").style.width) / 2) + "px";
                    document.getElementById("boton_comenzar_juego").style.visibility = "hidden";
                    //Se aplican los codigos HTML generados (solo si es la primera vez o si el ancho y alto del tablero han cambiado):
                    if (!mismos_tableros)
                    {
                        //Se aplica el codigo HTML generado a los ejes de coordenadas
                        document.getElementById("coordenadas_x_usuario").innerHTML = coordenadas_x_html_usuario;
                        document.getElementById("coordenadas_x_ordenador").innerHTML = coordenadas_x_html_ordenador;
                        document.getElementById("coordenadas_y").innerHTML = coordenadas_y_html;
                        //Se aplica el codigo HTML generado a las zonas invisibles de juego:
                        document.getElementById("zona_juego_usuario_invisible").innerHTML = mapa_vacio_html_invisible_usuario;
                        document.getElementById("zona_juego_ordenador_invisible").innerHTML = mapa_vacio_html_invisible_ordenador;
                        //Se aplica el codigo HTML generado a las zonas de juego:
                        document.getElementById("zona_juego_usuario").innerHTML = mapa_vacio_html_usuario;
                        document.getElementById("zona_juego_ordenador").innerHTML = mapa_vacio_html_ordenador;
                    }
                    //Si son los mismos tableros, solo borra las posibles imagenes (explosiones y X):
                    else
                    {
                        for (x = 0;  x < tablero_ancho * tablero_alto; x++)
                        {
                            //Si se ha escogido mostrar numeros en las casillas, el numero a mostrar es el de la casilla y si no estara vacio:
                            numero_a_mostrar = (mostrar_numero_celdas) ? x : "";
                            //Se vacian las celdas invisibles (en realidad les pone de contenido una imagen transparente para que detecte el onMouseOver cuando se arrastra un barco en Internet Explorer):
                            document.getElementById("celda_" + x + "_usuario_invisible").innerHTML = document.getElementById("celda_" + x + "_ordenador_invisible").innerHTML = '<img src="img/invisible.gif" alt="" style="position:absolute; left:0px; top:0px; width:' + celda_ancho + 'px; height:' + celda_alto + 'px;" title="( ' + contador_columna + ', ' + eval(tablero_alto - contador_fila - 1) + ' )">' + numero_a_mostrar;
                            //Borra las coordenadas, por si estaban marcadas:
                            document.getElementById("x_" + contador_columna + "_usuario").style.background = document.getElementById("x_" + contador_columna + "_ordenador").style.background = "#000011";
                            document.getElementById("y_" + contador_fila).style.background = "#000011";
                            //Incrementa una columna:
                            contador_columna++;
                            //Si se ha alcanzado el tope de columnas, se vuelve a la primera columna y se inrementa una fila:
                            if (contador_columna >= tablero_ancho) { contador_columna = 0; contador_fila++; }
                            //Se borran todas las celdas por si habia alguna marcada:
                            document.getElementById("celda_" + x + "_usuario").style.background = document.getElementById("celda_" + x + "_ordenador").style.background = "#6600ff";
                        }
                    }
                }
                
              
        // -->
        </script>
        <link rel="SHORTCUT ICON" href="favicon.ico">
    </head>
    <body onLoad="pagina_cargada = false; document.getElementById('reloj').style.visibility = 'hidden'; setTimeout('iniciar_juego_primera_vez(); pagina_cargada = true;', 10);" onMouseUp="campo_seleccionable = false;" onClick="campo_seleccionable = false;" onMouseMove="if (pagina_cargada) { arrastrar_opciones(event); arrastrar_alerta(event); arrastrar_barco(event, barco_arrastrandose); arrastrar_bomba(event); arrastrar_reloj(event); }" onContextMenu="return rotar_barco();" onKeyPress="javascript:tecla_pulsada(event, 'onkeypress');" onKeyDown="javascript:tecla_pulsada(event, 'onkeydown');" bgcolor="#9999aa" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <!-- Menu: -->
        <div id="juego_nuevo" style="left:8px; top:10px; width:110px; height:20px; position:absolute; border:0px; padding:0px; background:transparent; color:#000000; text-align:center; line-height:20px; text-decoration:none; font-family:arial; font-size:12px; cursor:pointer; cursor:hand; -moz-user-select:none; z-index:2;" title="Juego nuevo" onMouseOver="if (!juego_bloqueado) { this.style.fontWeight = 'bold'; }" onMouseOut="this.style.fontWeight = 'normal';" onClick="if (!juego_bloqueado && se_ha_comenzado && (!alerts_activados || alerts_activados && confirm('Pulsa aceptar para iniciar un juego nuevo. Se va a perder el actual.'))) { mostrar_mensaje('Cargando...'); setTimeout('iniciar_juego(true);', 10); }" onSelectStart="return false;">
            Nuevo juego
        </div>
        <div id="menu" style="left:110px; top:10px; width:65px; height:20px; position:absolute; border:0px; padding:0px; background:transparent; color:#000000; text-align:center; line-height:20px; text-decoration:none; font-family:arial; font-size:12px; cursor:pointer; cursor:hand; -moz-user-select:none; z-index:2;" title="Abrir opciones" onMouseOver="if (!juego_bloqueado) { this.style.fontWeight = 'bold'; }" onMouseOut="this.style.fontWeight = 'normal';" onClick="mostrar_ocultar_opciones();" onSelectStart="return false;">
            Opciones
        </div>
        <!-- Fin de Menu. -->
        <!-- Menu de opciones: -->
        <!-- <div id="menu_opciones" style="visibility:hidden; position:absolute; left:120px; top:30px; width:360px; height:484px; border:1px solid #0000aa; padding:0px; background:#a3a7d3; color:#000066; text-align:left; text-decoration:none; font-family:arial; font-size:12px; line-height:24px; filter:alpha(opacity=80); opacity:0.8; -moz-opacity:0.8; -khtml-opacity:0.8; cursor:crosshair; z-index:6;" onMouseUp="campo_seleccionable = false; arrastrando_opciones = false;" onMouseDown="if (campo_seleccionable) { campo_seleccionable = false; arrastrar_opciones(event, false); } else { arrastrando_opciones = true; }"> -->
        <div id="menu_opciones" style="visibility:hidden; position:absolute; left:120px; top:30px; width:360px; height:484px; border:1px solid #0000aa; padding:0px; background:#a3a7d3; color:#000066; text-align:left; text-decoration:none; font-family:arial; font-size:12px; line-height:24px; filter:alpha(opacity=80); opacity:0.8; -moz-opacity:0.8; -khtml-opacity:0.8; cursor:crosshair; z-index:6;" onMouseUp="campo_seleccionable = false; arrastrando_opciones = false;" onMouseDown="if (campo_seleccionable) { campo_seleccionable = false; arrastrando_opciones = false; } else { arrastrando_opciones = true; }">
            <div id="barra_menu_opciones" style="position:absolute; left:2px; top:2px; width:354px; height:24px; border:1px solid #0000aa; padding:0px; background:#8387b3; color:#000066; text-align:center; text-decoration:none; font-weight:bold; font-family:verdana; font-size:12px; line-height:24px; filter:alpha(opacity=80); opacity:0.8; -moz-opacity:0.8; -khtml-opacity:0.8; -moz-user-select:none; z-index:7;" title="Men&uacute; de opciones" onSelectStart="return false;">
                Men&uacute; de opciones
                <div id="boton_barra_menu_opciones" style="position:absolute; left:330px; top:1px; width:20px; height:20px; border:1px solid #0000aa; padding:0px; background:#8387b3; color:#000066; text-align:center; text-decoration:none; font-weight:bold; font-family:verdana; font-size:10px; line-height:20px; filter:alpha(opacity=80); opacity:0.8; -moz-opacity:0.8; -khtml-opacity:0.8; cursor:pointer; cursor:hand; -moz-user-select:none; z-index:8;" title="Cerrar men&uacute;" onMouseOver="this.style.background='#ff0000'; this.style.color='#ffffff'; this.style.border='1px solid #ffffff';" onMouseOut="this.style.background='#8387b3'; this.style.color='#000066'; this.style.border='1px solid #0000aa';" onSelectStart="return false;" onClick="mostrar_ocultar_opciones();">X</div>
            </div>
            <div id="cuerpo_menu_opciones" style="position:absolute; left:17px; top:30px; width:326px; border:0px; padding:0px; background:transparent; color:#000066; text-align:left; text-decoration:none; font-weight:normal; font-family:verdana; font-size:12px; line-height:24px; filter:alpha(opacity=80); opacity:0.8; -moz-opacity:0.8; -khtml-opacity:0.8; z-index:7;">
                <form style="display:inline;" onSubmit="aplicar_opciones(); return false;" align="center">
                   <center>
                       <fieldset style="width:310px; border:1px #000055 solid; cursor:crosshair;">
                           <legend style="width:310px; color:#0000aa; font-size:20px; font-weight:bold; text-align:center; -moz-user-select:none;" onSelectStart="return false;" title="Men&uacute;n de opciones">Opciones</legend>
                           <br>
                           <label for="tablero_ancho" style="line-height:12px; font-size:12px; cursor:pointer; cursor:hand;" accesskey="t" title="Ancho del tablero (n&uacute;mero de casillas)"><b style="-moz-user-select:none;" onSelectStart="return false;">&nbsp; Ancho del <u>t</u>ablero:</b> <input type="text" name="tablero_ancho" id="tablero_ancho" onMouseDown="campo_seleccionable = true;" onMouseUp="campo_seleccionable = false;" size="4" maxlength="3" style="height:22px; color:#0000aa; background:#ffffdd; font-family:courier; line-height:12px; font-size:12px; font-weight:bold;" accesskey="t"></label>
                           <br>
                           <br>
                           <label for="tablero_alto" style="line-height:12px; font-size:12px; cursor:pointer; cursor:hand;" accesskey="e" title="Alto del tablero (n&uacute;mero de casillas)"><b style="-moz-user-select:none;" onSelectStart="return false;">&nbsp; Alto d<u>e</u>l tablero:</b> <input type="text" name="tablero_alto" id="tablero_alto" onMouseDown="campo_seleccionable = true;" onMouseUp="campo_seleccionable = false;" size="4" maxlength="3" style="height:22px; color:#0000aa; background:#ffffdd; font-family:courier; line-height:12px; font-size:12px; font-weight:bold;" accesskey="e"></label>
                           <br>
                           <br>
                           <label for="permitir_bombardear_lo_bombardeado" style="line-height:12px; font-size:12px; cursor:pointer; cursor:hand;" accesskey="b" title="Permite volver a bombardear una casilla ya bombardeada"><input type="checkbox" name="permitir_bombardear_lo_bombardeado" id="permitir_bombardear_lo_bombardeado" onMouseDown="campo_seleccionable = true;" onMouseUp="campo_seleccionable = false;" accesskey="b" style="cursor:pointer; cursor:hand;"><b style="-moz-user-select:none;" onSelectStart="return false;"> Poder re-<u>b</u>ombardear</b></label>
                           <br>
                           <br>
                           <label for="permitir_poner_barcos_adyacentes" style="line-height:12px; font-size:12px; cursor:pointer; cursor:hand;" accesskey="y" title="Permite poner barcos en casillas contiguas"><input type="checkbox" name="permitir_poner_barcos_adyacentes" id="permitir_poner_barcos_adyacentes" onMouseDown="campo_seleccionable = true;" onMouseUp="campo_seleccionable = false;" accesskey="y" style="cursor:pointer; cursor:hand;"><b style="-moz-user-select:none;" onSelectStart="return false;"> Poder poner barcos ad<u>y</u>acentes</b></label>
                           <br>
                           <br>
                           <label for="marcar_bombardeado" style="line-height:12px; font-size:12px; cursor:pointer; cursor:hand;" accesskey="m" title="Marcar casillas ya bombardeadas"><input type="checkbox" name="marcar_bombardeado" id="marcar_bombardeado" onMouseDown="campo_seleccionable = true;" onMouseUp="campo_seleccionable = false;" accesskey="m" style="cursor:pointer; cursor:hand;"><b style="-moz-user-select:none;" onSelectStart="return false;"> <u>M</u>arcar casillas ya bombardeadas</b></label>
                           <br>
                           <br>
                           <label for="primer_jugador" style="line-height:12px; font-size:12px; cursor:pointer; cursor:hand;" accesskey="i" title="Qu&eacute; usuario tirar&aacute; primero">
                           <b style="-moz-user-select:none;" onSelectStart="return false;">&nbsp; Pr<u>i</u>mer jugador:</b>
                           <select id="primer_jugador" name="primer_jugador" accesskey="i" onMouseDown="campo_seleccionable = true;" onMouseUp="campo_seleccionable = false;" style="cursor:pointer; cursor:hand;">
                               <option value="usuario">Usuario</option>
                               <option value="ordenador">Ordenador</option>
                           </select>
                           </label>
                           <br>
                           <br>
                           <label for="dificultad" style="line-height:12px; font-size:12px; cursor:pointer; cursor:hand;" accesskey="d" title="Nivel de dificultad">
                           <b style="-moz-user-select:none;" onSelectStart="return false;">&nbsp; <u>D</u>ificultad:</b>
                           <select id="dificultad" name="dificultad" accesskey="d" onMouseDown="campo_seleccionable = true;" onMouseUp="campo_seleccionable = false;" style="cursor:pointer; cursor:hand;">
                               <option value="facil">F&aacute;cil</option>
                               <option value="normal">Normal</option>
                               <option value="dificil">Dif&iacute;cil</option>
                           </select>
                           </label>
                           <br>
                           <br>
                           <center><input type="submit" value="Aplicar" name="boton_aplicar" style="height:24px; color:#aa0000; font-weight:bold; text-align:center; line-height:12px; font-size:12px; font-family:arial; cursor:pointer; cursor:hand; -moz-user-select:none;" accesskey="a" onSelectStart="return false;" title="Aplicar opciones"></center>
                           <br>
                       </fieldset>
                    </center>
                </form>
            </div>
        </div>
        <!-- Fin de Menu de opciones. -->
        <!-- Sombra del menu de opciones: -->
        <div id="menu_opciones_sombra" style="visibility:hidden; position:absolute; left:124px; top:34px; width:360px; height:484px; border:0px; padding:0px; background:#111111; color:#000066; text-align:left; text-decoration:none; font-family:arial; font-size:12px; line-height:24px; filter:alpha(opacity=50); opacity:0.5; -moz-opacity:0.5; -khtml-opacity:0.5; cursor:crosshair; z-index:5;" onMouseUp="campo_seleccionable = false; arrastrando_opciones = false;" onMouseDown="if (campo_seleccionable) { campo_seleccionable = false; arrastrando_opciones = false; } else { arrastrando_opciones = true; }"></div>
        <!-- Fin de Sombra del menu de opciones. -->
        <!-- Mensaje: -->
        <div id="mensaje" style="visibility:visible; position:absolute; left:180px; top:2px; width:450px; height:56px; border:0px; padding:0px; background:#23aacc; color:#550000; text-align:center; text-decoration:none; font-weight:bold; font-family:arial; font-size:16px; line-height:56px; filter:alpha(opacity=50); opacity:0.5; -moz-opacity:0.5; -khtml-opacity:0.5; z-index:8;">Inicializando...</div>
        <!-- Fin de Mensaje. -->
        <!-- Substituto de alert: -->
        <div id="alerta" style="visibility:hidden; background:#aaaacc; color:#aa0000; left:235px; top:125px; width:444px; height:240px; padding:0px; position:absolute; font-size:18px; font-style:normal; font-weight:bold; line-height:20px; text-align:center; filter:alpha(opacity=80); opacity:0.8; -moz-opacity:0.8; -khtml-opacity:0.8; -moz-user-select:none; cursor:crosshair; z-index:15;" onMouseUp="campo_seleccionable = false; arrastrando_alerta = false;" onMouseDown="if (campo_seleccionable) { campo_seleccionable = false; arrastrando_alerta = false; } else { arrastrando_alerta = true; };" onSelectStart="return false;" onClick="if (document.getElementById('alerta').style.visibility == 'visible') { document.getElementById('formulario_alerta').boton_alerta.focus(); }">
            <div id="boton_cerrar_mensaje_alerta" style="position:absolute; left:420px; top:5px; width:15px; height:10px; border:1px solid #000000; padding:0px; background:#dddddd; color:#000066; text-align:center; text-decoration:none; font-weight:bold; font-family:verdana; font-size:8px; line-height:8px; cursor:pointer; cursor:hand; -moz-user-select:none; z-index:8;" title="Close" onMouseOver="this.style.background='#ffffff'; this.style.color='#0000ff'; this.style.border='1px solid #0000ff';" onMouseOut="this.style.background='#dddddd'; this.style.color='#000066'; this.style.border='1px solid #000000';" onSelectStart="return false;" onClick="if (juego_bloqueado) { mostrar_mensaje('Cargando...'); setTimeout('iniciar_juego(true); mostrar_mensaje(\'\');', 10); } document.getElementById('alerta').style.visibility = 'hidden'; document.getElementById('alerta_sombra').style.visibility = 'hidden';">X</div>
            <div id="alerta_mensaje" style="background:transparent; color:#aa0000; left:10px; top:20px; width:424px; height:210px; padding:0px; position:absolute; font-size:14px; font-style:normal; font-weight:bold; line-height:20px; text-align:center; z-index:10;">
            </div>
            <div style="position:absolute; left:0px; top:200px; width:430px; height:30px; z-index:16;">
                <form style="display:inline;" id="formulario_alerta" onSubmit="if (juego_bloqueado) { mostrar_mensaje('Cargando...'); setTimeout('iniciar_juego(true); mostrar_mensaje(\'\');', 10); } document.getElementById('alerta').style.visibility = 'hidden'; document.getElementById('alerta_sombra').style.visibility = 'hidden'; return false;" align="center">
                <center><input type="submit" value="Aceptar" name="boton_alerta" style="height:24px; color:#aa0000; font-weight:bold; text-align:center; line-height:12px; font-size:12px; font-family:arial; cursor:pointer; cursor:hand; -moz-user-select:none;" accesskey="a" onSelectStart="return false;" title="Cerrar ventana"></center>
            </div>
        </div>
        <div id="alerta_sombra" style="visibility:hidden; background:#aaaaaa; color:#aa0000; left:239px; top:129px; width:444px; height:240px; padding:0px; position:absolute; font-size:18px; font-style:normal; font-weight:bold; line-height:20px; text-align:center; filter:alpha(opacity=50); opacity:0.5; -moz-opacity:0.5; -khtml-opacity:0.5; -moz-user-select:none; cursor:crosshair; z-index:14;" onMouseUp="campo_seleccionable = false; arrastrando_alerta = false;" onMouseDown="if (campo_seleccionable) { campo_seleccionable = false; arrastrando_alerta = false; } else { arrastrando_alerta = true; }" onSelectStart="return false;" onClick="if (document.getElementById('alerta').style.visibility == 'visible') { document.getElementById('formulario_alerta').boton_alerta.focus(); }">
        </div>
        <!-- Fin de Substituto de alert. -->
        <!-- Zona de juego: -->
        <div id="zona_juego_usuario" style="left:60px; top:30px; width:450px; height:450px; visibility:visible; position:absolute; border:0px; padding:0px; background:url('img/fondo.jpg'); color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:10px; z-index:1;"></div>
        <div id="zona_juego_usuario_invisible" style="left:60px; top:30px; width:450px; height:450px; visibility:visible; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:10px; text-decoration:none; font-family:verdana; font-size:10px; z-index:3;"></div>
        <div id="zona_juego_ordenador" style="left:510px; top:30px; width:450px; height:450px; visibility:visible; position:absolute; border:0px; padding:0px; background:url('img/fondo.jpg'); color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:10px; z-index:1;"></div>
        <div id="zona_juego_ordenador_invisible" style="left:510px; top:30px; width:450px; height:450px; visibility:visible; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:10px; text-decoration:none; font-family:verdana; font-size:10px; z-index:3;"></div>
        <!-- Fin de Zona de juego. -->
        <!-- Coordenadas: -->
        <div id="coordenadas_x_usuario" style="left:60px; top:480px; width:450px; height:40px; visibility:visible; position:absolute; border:0px; padding:0px; background:#550000; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:default;"></div>
        <div id="coordenadas_x_ordenador" style="left:510px; top:480px; width:450px; height:40px; visibility:visible; position:absolute; border:0px; padding:0px; background:#550000; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:default;"></div>
        <div id="coordenadas_y" style="left:10px; top:30px; width:40px; height:450px; visibility:visible; position:absolute; border:0px; padding:0px; background:#550000; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:default;"></div>
        <!-- Fin de Coordenadas. -->
        <!-- Barcos en horizontal: -->
        <div id="barcos" style="left:60px; top:580px; width:660px; height:40px; visibility:visible; position:absolute; border:0px; padding:0px; background:#aabbcc; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:default;">
        </div>
        <div id="barco_1_invisible" onClick="escoger_barco(0);" style="left:10px; top:0px; width:40px; height:40px; visibility:hidden; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:4; cursor:pointer; cursor:hand;" title=""><img src="img/invisible.gif" alt="" id="barco_1_img_invisible" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;" title=""></div>
        <div id="barco_1" onClick="escoger_barco(1);" style="left:10px; top:0px; width:40px; height:40px; visibility:visible; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
            <div id="barco_1_1" style="left:0px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_derecha.gif" alt="&gt;" title="&lt;=&gt;" id="barco_1_1_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
        </div>
        <div id="barco_2_invisible" onClick="escoger_barco(0);" style="left:10px; top:0px; width:40px; height:40px; visibility:hidden; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:4; cursor:pointer; cursor:hand;" title=""><img src="img/invisible.gif" alt="" id="barco_2_img_invisible" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;" title=""></div>
        <div id="barco_2" onClick="escoger_barco(2);" style="left:60px; top:0px; width:80px; height:40px; visibility:visible; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
            <div id="barco_2_1" style="left:0px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_izquierda.gif" title="&lt;==&gt;" alt="&lt;" id="barco_2_1_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_2_2" style="left:40px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_derecha.gif" title="&lt;==&gt;" alt="&gt;" id="barco_2_2_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
        </div>
        <div id="barco_3_invisible" onClick="escoger_barco(0);" style="left:10px; top:0px; width:40px; height:40px; visibility:hidden; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:4; cursor:pointer; cursor:hand;" title=""><img src="img/invisible.gif" alt="" id="barco_3_img_invisible" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;" title=""></div>
        <div id="barco_3" onClick="escoger_barco(3);" style="left:150px; top:0px; width:120px; height:40px; visibility:visible; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
            <div id="barco_3_1" style="left:0px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_izquierda.gif" title="&lt;===&gt;" alt="&lt;" id="barco_3_1_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_3_2" style="left:40px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_medio.gif" title="&lt;===&gt;" alt="=" id="barco_3_2_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_3_3" style="left:80px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_derecha.gif" title="&lt;===&gt;" alt="&gt;" id="barco_3_3_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
        </div>
        <div id="barco_4_invisible" onClick="escoger_barco(0);" style="left:10px; top:0px; width:40px; height:40px; visibility:hidden; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:4; cursor:pointer; cursor:hand;" title=""><img src="img/invisible.gif" alt="" id="barco_4_img_invisible" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;" title=""></div>
        <div id="barco_4" onClick="escoger_barco(4);" style="left:280px; top:0px; width:160px; height:40px; visibility:visible; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
            <div id="barco_4_1" style="left:0px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_izquierda.gif" title="&lt;====&gt;" alt="&lt;" id="barco_4_1_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_4_2" style="left:40px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_medio.gif" alt="=" title="&lt;====&gt;" id="barco_4_2_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_4_3" style="left:80px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_medio.gif" title="&lt;====&gt;" alt="=" id="barco_4_3_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_4_4" style="left:120px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_derecha.gif" title="&lt;====&gt;" alt="&gt;" id="barco_4_4_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
        </div>
        <div id="barco_5_invisible" onClick="escoger_barco(0);" style="left:10px; top:0px; width:40px; height:40px; visibility:hidden; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:4; cursor:pointer; cursor:hand;" title=""><img src="img/invisible.gif" alt="" id="barco_5_img_invisible" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;" title=""></div>
        <div id="barco_5" onClick="escoger_barco(5);" style="left:450px; top:0px; width:200px; height:40px; visibility:visible; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
            <div id="barco_5_1" style="left:0px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_izquierda.gif" title="&lt;=====&gt;" alt="&lt;" id="barco_5_1_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_5_2" style="left:40px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_medio.gif" title="&lt;=====&gt;" alt="=" id="barco_5_2_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_5_3" style="left:80px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_medio.gif" title="&lt;=====&gt;" alt="=" id="barco_5_3_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_5_4" style="left:120px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_medio.gif" title="&lt;=====&gt;" alt="=" id="barco_5_4_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_5_5" style="left:160px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_derecha.gif" title="&lt;=====&gt;" alt="&gt;" id="barco_5_5_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
        </div>
        <!-- Fin de Barcos en horizontal. -->
        <!-- Barcos en vertical: -->
        <div id="barco_1_vertical" onClick="escoger_barco(1);" style="left:10px; top:0px; width:40px; height:40px; visibility:hidden; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
            <div id="barco_1_1_vertical" style="left:0px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_arriba.gif" title="&lt;=&gt;" alt="&gt;" id="barco_1_1_img_vertical" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
        </div>
        <div id="barco_2_vertical" onClick="escoger_barco(2);" style="left:60px; top:0px; width:80px; height:40px; visibility:hidden; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
            <div id="barco_2_1_vertical" style="left:0px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_arriba.gif" title="&lt;==&gt;" alt="&lt;" id="barco_2_1_img_vertical" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_2_2_vertical" style="left:0px; top:40px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_abajo.gif" title="&lt;==&gt;" alt="&gt;" id="barco_2_2_img_vertical" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
        </div>
        <div id="barco_3_vertical" onClick="escoger_barco(3);" style="left:150px; top:0px; width:120px; height:40px; visibility:hidden; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
            <div id="barco_3_1_vertical" style="left:0px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_arriba.gif" title="&lt;===&gt;" alt="&lt;" id="barco_3_1_img_vertical" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_3_2_vertical" style="left:0px; top:40px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_medio_vertical.gif" title="&lt;===&gt;" alt="=" id="barco_3_2_img_vertical" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_3_3_vertical" style="left:0px; top:80px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_abajo.gif" title="&lt;===&gt;" alt="&gt;" id="barco_3_3_img_vertical" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
        </div>
        <div id="barco_4_vertical" onClick="escoger_barco(4);" style="left:280px; top:0px; width:160px; height:40px; visibility:hidden; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
            <div id="barco_4_1_vertical" style="left:0px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_arriba.gif" title="&lt;====&gt;" alt="&lt;" id="barco_4_1_img_vertical" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_4_2_vertical" style="left:0px; top:40px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_medio_vertical.gif" title="&lt;====&gt;" alt="=" id="barco_4_2_img_vertical" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_4_3_vertical" style="left:0px; top:80px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_medio_vertical.gif" title="&lt;====&gt;" alt="=" id="barco_4_3_img_vertical" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_4_4_vertical" style="left:0px; top:120px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_abajo.gif" title="&lt;====&gt;" alt="&gt;" id="barco_4_4_img_vertical" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
        </div>
        <div id="barco_5_vertical" onClick="escoger_barco(5);" style="left:450px; top:0px; width:200px; height:40px; visibility:hidden; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
            <div id="barco_5_1_vertical" style="left:0px; top:0px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_arriba.gif" title="&lt;=====&gt;" alt="&lt;" id="barco_5_1_img_vertical" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_5_2_vertical" style="left0px; top:40px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_medio_vertical.gif" title="&lt;=====&gt;" alt="=" id="barco_5_2_img_vertical" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_5_3_vertical" style="left:0px; top:80px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_medio_vertical.gif" title="&lt;=====&gt;" alt="=" id="barco_5_3_img_vertical" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_5_4_vertical" style="left:0px; top:120px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_medio_vertical.gif" title="&lt;=====&gt;" alt="=" id="barco_5_4_img_vertical" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
            <div id="barco_5_5_vertical" style="left:0px; top:160px; width:40px; height:40px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; cursor:pointer; cursor:hand;">
                <img src="img/barco_abajo.gif" alt="&gt;" title="&lt;=====&gt;" id="barco_5_5_img_vertical" style="position:absolute; left:0px; top:0px; width:40px; height:40px; cursor:pointer; cursor:hand;">
            </div>
        </div>
        <!-- Fin de Barcos en vertical. >
        <!-- Boton para rotar el barco: -->
        <div id="boton_rotar" onClick="rotar_barco();" style="left:0px; top:0px; width:40px; height:40px; visibility:hidden; position:absolute; border:0px; padding:0px; background:#aabbcc; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:4; filter:alpha(opacity=60); opacity:0.6; -moz-opacity:0.6; -khtml-opacity:0.6; cursor:pointer; cursor:hand;" title="Rotar barco (bot&oacute;n derecho en Internet Explorer o Mozilla Firefox). Tecla r&aacute;pida: Shift (o Control, Alt...)">
            <img src="img/rotar.gif" alt="@" id="boton_rotar_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px;" title="Rotar barco (bot&oacute;n derecho en Internet Explorer o Mozilla Firefox). Tecla r&aacute;pida: Shift (o Control, Alt...)">
        </div>
        <!-- Fin de Boton para rotar el barco. -->
        <!-- Imagen de la bomba: -->
        <div id="bomba" style="left:0px; top:0px; width:40px; height:40px; visibility:hidden; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; filter:alpha(opacity=60); opacity:0.6; -moz-opacity:0.6; -khtml-opacity:0.6; cursor:crosshair;" title="Bomba">
            <img src="img/bomba.gif" alt="X" id="bomba_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px;" title="Bomba">
        </div>
        <!-- Fin de Imagen de la bomba. -->
        <!-- Imagen del reloj de espera: -->
        <div id="reloj" style="left:0px; top:0px; width:40px; height:40px; visibility:hidden; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:20; filter:alpha(opacity=80); opacity:0.8; -moz-opacity:0.8; -khtml-opacity:0.8; cursor:wait;" title="">
            <img src="img/reloj.gif" alt="X" id="reloj_img" style="position:absolute; left:0px; top:0px; width:40px; height:40px;" title="">
        </div>
        <!-- Fin de Imagen del reloj de espera. -->
        <!-- Boton para iniciar el juego: -->
        <div id="boton_comenzar_juego" onClick="mostrar_mensaje('Procesando...'); setTimeout('comenzar_batalla();', 10)" onMouseOver="this.style.border='1px #00aacc solid'; this.style.background='#bbccdd'; this.style.color='#ff0000';" onMouseOut="this.style.border='1px #000000 solid'; this.style.background='#aabbcc'; this.style.color='#cc0000';" onSelectStart="return false;" style="left:0px; top:0px; width:200px; height:36px; visibility:hidden; position:absolute; border:1px #000000 solid; padding:0px; background:#aabbcc; color:#cc0000; text-align:center; line-height:40px; text-decoration:none; font-family:verdana; font-size:20px; z-index:2; filter:alpha(opacity=60); opacity:0.6; -moz-opacity:0.6; -khtml-opacity:0.6; cursor:pointer; cursor:hand;" title="Comenzar batalla">Comenzar batalla</div>
        <!-- Fin de Boton para iniciar el juego. -->
        <!-- Precarga las imagenes en hidden aunque no se usen aqui, para que tarden menos en cargarse: -->
        <img src="img/x.gif" style="visibility:hidden; width:40px; height:40px;">
        <img src="img/boom.gif" style="visibility:hidden; width:40px; height:40px;">
        <!-- Fin de Precarga las imagenes en hidden aunque no se usen aqui, para que tarden menos en cargarse. -->
        <!-- Informacion: -->
        <div id="informacion" style="left:10px; top:490px; height:0px; position:absolute; border:0px; padding:0px; background:transparent; color:#333333; text-align:left; line-height:20px; text-decoration:none; font-family:verdana; font-size:10px; z-index:3;">
            &copy; <b>Hundiyas</b> 0.29a por <i>Joan Alba Maldonado</i> (<a href="mailto:granvino@granvino.com">granvino@granvino.com</a>) &nbsp;<sup>(100% DHTML)</sup>
            <br>&nbsp;&nbsp;- Prohibido publicar, reproducir o modificar sin citar expresamente al autor original.
            <br>
            &nbsp;&nbsp;<i>Dedicado a Yasmina Llaveria del Castillo</i>
        </div>
        <!-- Fin de Informacion. -->
    </body>
</html>
