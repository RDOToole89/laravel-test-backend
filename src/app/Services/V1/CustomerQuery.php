<?php

// Define the namespace for this class to organize it within the app's structure.
namespace App\Services\V1;

// Import the Request class from the Laravel framework to handle HTTP requests.
use Illuminate\Http\Request;

// Define the CustomerQuery class.
class CustomerQuery {
  // Define an array to list 'safe' query parameters and their corresponding operators.
  // These are the only parameters that are allowed for querying to prevent SQL injection or unwanted data exposure.
  protected $safeParms = [
    'name' => ['eq'],       // Only equality checks are allowed for 'name'.
    'type' => ['eq'],       // Only equality checks are allowed for 'type'.
    'email' => ['eq'],      // Only equality checks are allowed for 'email'.
    'address' => ['eq'],    // Only equality checks are allowed for 'address'.
    'city' => ['eq'],       // Only equality checks are allowed for 'city'.
    'state' => ['eq'],      // Only equality checks are allowed for 'state'.
    'postalCode' => ['eq', 'gr', 'lt']  // Equality, greater than, and less than checks are allowed for 'postalCode'.
  ];

  // Define a mapping from query parameter names to database column names.
  // This is useful when the parameter name does not exactly match the column name in the database.
  protected $columnMap = [
    'postalCode' => 'postal_code'  // Maps 'postalCode' parameter to 'postal_code' column.
  ];

  // Define a mapping from operator codes used in the query parameters to actual SQL operators.
  protected $operatorMap = [
    'eq' => '=',  // 'eq' stands for equals.
    'lt' => '<',  // 'lt' stands for less than.
    'lte' => '≤', // 'lte' stands for less than or equal to.
    'gt'=> '>',   // 'gt' stands for greater than.
    'gte' => '≥'  // 'gte' stands for greater than or equal to.
  ];

  // Define a method to transform the HTTP request into a query array that can be used with Eloquent.
  public function transform(Request $request) {
    $eloQuery = []; // Initialize an empty array to hold the query conditions.

    // Loop over each of the safe parameters to check if they are present in the request.
    foreach ($this->safeParms as $parm => $operators) {
        $query = $request->query($parm); // Retrieve the query parameter value from the request.

        // If the query parameter is not set, skip this iteration and continue with the next one.
        if(!isset($query)) {
          continue;
        }

        // Determine the actual database column name by checking if there's a mapping defined.
        // If not, use the parameter name as is.
        $column = $this->columnMap[$parm] ?? $parm;

        // Loop over the operators allowed for this parameter.
        foreach ($operators as $operator) {
          // Check if the operator exists in the query. If so, add it to the $eloQuery array.
          if (isset($query[$operator])) {
            // Note: There's a mistake here. It should be `$this->operatorMap[$operator]` instead of `$this->operatorMap($operator)`.
            $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
          }
        }
    }
    
    return $eloQuery; // Return the array of conditions to be used in an Eloquent query.
  }
}
