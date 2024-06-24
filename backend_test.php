<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculate Optimal Cost</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
            margin: 0;
        }
        .calculator {
            width: 100%;
            max-width: 600px;
            background-color: rgba(255, 255, 255, 0.9); 
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.2);
        }
        h2 {
            font-size: 28px;
            color: #007bff; 
            text-align: center; 
            margin-bottom: 20px;
        }
        label, p {
            font-size: 20px; 
            font-weight: bold;
        }
        .result {
            margin-top: 20px;
        }
        .form-control {
            width: 100%;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .text-danger {
            color: #dc3545;
            font-weight: bold;
            float: right;
        }
        .list-group-item {
            border: none; 
        }
        .total-cost {
            text-align: left; 
        }
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        input[type=number] {
            -moz-appearance: textfield; 
        }
    </style>
</head>
<body>
    <div class="calculator">
        <h2 class="mb-4">Calculate Optimal Cost for Car Rental</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group">
                <label for="seats">Number of seats:</label>
                <input type="number" class="form-control" id="seats" name="seats" required>
            </div>
            <button type="submit" class="btn btn-primary">Calculate</button>
        </form>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function calculate_optimal_cost($seats) {
        $car_types = [
            ["size" => "L", "capacity" => 15, "cost" => 12000],
            ["size" => "M", "capacity" => 10, "cost" => 8000],
            ["size" => "S", "capacity" => 5, "cost" => 5000],
        ];

        // Initialize variables to store the cheapest deal
        $cheapest_result = [];
        $cheapest_total_cost = PHP_INT_MAX; // Start with a very high number

        // Iterate over all combinations of car rentals
        foreach ($car_types as $car) {
            $current_result = [];
            $current_total_cost = 0;
            $remaining_seats = $seats;

            foreach ($car_types as $current_car) {
                if ($remaining_seats <= 0) {
                    break;
                }
                $num_cars = floor($remaining_seats / $current_car["capacity"]);
                if ($current_car === $car) {
                    $num_cars = $num_cars + 1;
                }
                if ($num_cars > 0) {
                    $current_result[] = [$current_car["size"], $num_cars];
                    $current_total_cost += $num_cars * $current_car["cost"];
                    $remaining_seats -= $num_cars * $current_car["capacity"];
                }
            }

            if ($remaining_seats > 0) {
                $current_result[] = [$car_types[count($car_types) - 1]["size"], 1];
                $current_total_cost += $car_types[count($car_types) - 1]["cost"];
            }

            // Check if the current combination is cheaper
            if ($current_total_cost < $cheapest_total_cost) {
                $cheapest_result = $current_result;
                $cheapest_total_cost = $current_total_cost;
            }
        }

        // Output the cheapest result found
        echo '<div class="result">';
        echo '<h3 style="font-size:23px;">Result:</h3>';
        echo '<ul class="list-group mb-3">';
        foreach ($cheapest_result as $car) {
            echo '<li class="list-group-item" style="font-size: 19px;  padding-left: 0;">' . $car[0] . ' x ' . $car[1] . '</li>';
        }
        echo '</ul>';
        echo '<div class="total-cost">';
        echo '<p>Total Cost: <span class="text-danger">PHP <strong>' . number_format($cheapest_total_cost) . '</strong></span></p>';
        echo '</div>';
        echo '</div>';
    }

    $seats = $_POST["seats"];
    calculate_optimal_cost((int)$seats);
}
?>

    </div>
</body>
</html>
