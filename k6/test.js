import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
    stages: [
        { duration: '30s', target: 1000 }, // 30초 동안 1000명의 사용자로 증가
        { duration: '1m', target: 1000 },  // 1분 동안 1000명의 사용자 유지
        { duration: '10s', target: 0 },   // 10초 동안 사용자를 0으로 감소
    ],
};

export default function () {
    let res = http.get('http://192.168.59.100:30080'); // 테스트할 URL
    check(res, { 'status was 200': (r) => r.status == 200 });
    sleep(1);
}
