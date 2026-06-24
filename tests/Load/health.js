import http from 'k6/http';
import { check, sleep } from 'k6';
import { Rate, Trend } from 'k6/metrics';

const BASE_URL = __ENV.BASE_URL || 'https://lumexa.saeedhosan.com';

const errorRate = new Rate('errors');
const responseTime = new Trend('response_time');

export const options = {
    stages: [
        { duration: '30s', target: 10 },
        { duration: '30s', target: 20 },
        { duration: '30s', target: 0 },
    ],
    thresholds: {
        errors: ['rate<0.05'],
        response_time: ['p(95)<500'],
        http_req_duration: ['p(95)<1000'],
    },
};

export default function () {
    const response = http.get(`${BASE_URL}/health`);

    check(response, {
        'status is 200': (r) => r.status === 200,
        'body contains status ok': (r) => r.json('status') === 'ok',
    });

    errorRate.add(response.status !== 200);
    responseTime.add(response.timings.duration);

    sleep(1);
}
