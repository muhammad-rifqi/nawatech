import http from 'k6/http';
import { sleep } from 'k6';

export const options = {
  stages: [
  { duration: '1m', target: 20 },
],
};

//   { duration: '1m', target: 20 },
//   { duration: '1m', target: 30 },

export default function () {
  http.get('http://localhost:8000/api/orders');
  sleep(1);
}