<!doctype html>
<html>
    <head>
    	<!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
        
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
        
        <!-- Application Stylesheet -->
        <link rel="stylesheet" href="/css/main.css" type="text/css" />
    </head>
    <body>
        <div class="container-fluid">
            <div class="page-header">
                <h1>USCHS Computer Cart/Lab Reservation System</h1>
            </div>
            <div class="row">
                <div id="reservationsViewContainer" class="col-md-8">
                    <form role="form" class="form-inline" id="filterByDateForm">
                        <div class="form-group">
                            <label for="filterByDate">Date</label>
                            <input type="date" class="form-control" id="filterByDate" placeholder="Filter by date" required>
                            <button class="btn btn-primary" data-action="filterByDate">Filter by Date</button>
                        </div>
                    </form>
                    <form role="form" class="form-inline" id="filterByCartForm">
                        <div class="form-group">
                            <label for="filterByCart">Cart </label>
                            <input type="text" class="form-control" id="filterByCart" placeholder="Cart Name" required>
                            <button class="btn btn-primary" data-action="filterByCart">Filter by Cart</button>
                        </div>
                    </form>
                    <table class="table table-bordered" id="reservationsView">
                        <thead>
                            <tr>
                                <td>Cart Name</td>
                                <td>Teacher</td>
                                <td>Room Number</td>
                                <td>Date</td>
                                <td>Mods</td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div><!-- ./col-md-8 -->
                <div id="formViewContainer" class="col-md-4">
                    <form action="#" role="form" id="reservationForm">
                        <header><h3>Reserve a Cart/Lab</h3></header>
                        <!-- Text input-->
                        <div class="form-group">
                            <label for="teacherName">Teacher Name</label>
                            <input class="form-control input-md" id="teacherName" name="teacher_name" placeholder="Mr. Binkley" 
                                required type="text">
                        </div>
                        <div class="form-group">
                            <label for="teacherEmail">Teacher Email</label>
                            <input type="email" class="form-control input-md" id="teacherEmail" name="teacher_email" 
                                placeholder="name@uscsd.k12.pa.us" required>
                        </div>
                        <!-- Text input-->
                        <div class="form-group">
                            <label for="date">Date Needed</label>
                            <input class="form-control input-md" id="date" name="date"
                                placeholder="MM/DD/YYYY" type="text" required>
                        </div>
                        <!-- Text input-->
                        <div class="form-group">
                            <label for="room">Room Number</label>
                            <input class="form-control input-md" id="room" name="room_number"
                                    placeholder="129" type="text" required>
                        </div>
                        <!-- Multiple Checkboxes -->
                        <div class="form-group">
                            <label for="mods">Mods Needed</label>
                            <table class="table">
                                @for ($i = 1; $i < 17; $i += 4)
                                    <tr>
                                        <td>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="mods[]" value="{{ $i }}">
                                                    {{ $i }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="mods[]" value="{{ $i + 1 }}">
                                                    {{ $i + 1 }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="mods[]" value="{{ $i + 2 }}">
                                                    {{ $i + 2 }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="mods[]" value="{{ $i + 3 }}">
                                                    {{ $i + 3 }}
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endfor
                            </table>
                        </div>
                        <!-- Select Basic -->
                        <div class="form-group">
                            <label for="cart" required>Cart</label>
                            <select class="form-control input-md" id="cart" name="cart_id">
                                <option value="53">CART 1 (TESTING)</option>
                            </select>
                        </div>
                        <!-- Button -->
                        <div class="form-group">
                            <label for="submit"></label>
                            <button class="btn btn-primary" id="submit" name="submit" data-action="createReservation">Submit</button>
                        </div>
                    </form><!-- ./reservationForm -->
                </div><!-- ./col-md-4 -->
            </div><!-- ./row -->
            
        </div><!-- ./container-fluid -->
        
        <script type="text/x-handlebars-template" data-template-name="reservationTemplate">
            <tr>
                <td>@{{ cart_name }}</td>
                <td>@{{ teacher_name }}</td>
                <td>@{{ room_number }}</td>
                <td>@{{ date }}</td>
                <td>@{{ mods }}</td>
            </tr>
        </script>
        <script data-main="/js/app.js" src="/js/require.min.js"></script>
        <script>
            requirejs(["app"], function(App)
            {
                var reservationForm = $("#reservationForm"),
                    reservationsView = $("#reservationsView tbody");
                
                $('[data-action="filterByDate"]').on("click", function(e)
                {
                    e.preventDefault();
                
                    var $this = $(this),
                        dateInput = $this.prev("input");
                
                    App.reservations.filterByDate(reservationsView, dateInput.val()).done(function()
                    {
                        // TODO: success handler
                        console.log("success");
                    }).fail(function()
                    {
                        // TODO: error handler
                        console.log("error");
                    });
                });
                
                $('[data-action="createReservation"]').on("click", function(e)
                {
                    e.preventDefault();
                    
                    var $this = $(this),
                        formData = $this.parents("form").serializeObject();
                    
                    App.reservations.create(formData).done(function()
                    {
                       // TODO: success handler
                    }).fail(function()
                    {
                        // TODO: error handler
                    });
                });
            });
        </script>
    </body>
</html>