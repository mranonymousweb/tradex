import { DECIMAL_COUNT } from "src/common/constant/decimal-count";

export function getRandomNumber(min: number, max: number): number {
  const rand = Math.random() * (max - min) + min;
  return parseFloat(rand.toFixed(DECIMAL_COUNT));
}

export function getRandomNumberWithDecimal(min: number, max: number, decimalCount: number): number {
  const rand = Math.random() * (max - min) + min;
  return parseFloat(rand.toFixed(decimalCount));
}