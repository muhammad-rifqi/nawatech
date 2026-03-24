import http from 'k6/http';
import { sleep } from 'k6';

export const options = {
  stages: [
    { duration: '10s', target: 1 },   
    { duration: '4m50s', target: 500 }
  ],
};

export default function () {
  http.get('http://localhost:8000/api/orders');
  sleep(1);
}


// import http from 'k6/http';
// import { sleep } from 'k6';

// export const options = {
//   vus: 500,
//   duration: '10m'
// };

// export default function () {

//   const url = 'http://localhost:8000/api/store';

//   const payload = JSON.stringify([
//   {
//     "user_id": 988,
//     "status": "completed",
//     "items": [
//       {"product_id": 301, "quantity": 2, "price": 50000},
//       {"product_id": 302, "quantity": 1, "price": 25000}
//     ]
//   },
//   {
//     "user_id": 985,
//     "items": [
//       {"product_id": 303, "quantity": 1, "price": 75000}
//     ]
//   }
// ]);

//   const params = {
//     headers: {
//       'Content-Type': 'application/json',
//     },
//   };

//   http.post(url, payload, params);

//   sleep(1);
// }