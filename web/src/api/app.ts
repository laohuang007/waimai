
import { request } from "@/utils/request";

export function config(data: any) {
  return request({
    url: "/order/config",
    method: "post",
    data,
  });
}
export function details(data: any) {
  return request({
    url: "/order/details",
    method: "post",
    data,
  });
}
export function paypal(data: any) {
  return request({
    url: "/order/paypal",
    method: "post",
    data,
  });
}
