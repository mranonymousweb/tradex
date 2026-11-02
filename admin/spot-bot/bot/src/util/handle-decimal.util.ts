// export function handleDecimal(price: string): string {
//     const decimals: string[] = price.split(".");
//     let count = 2;

//     if (decimals.length < 2) {
//         return price;
//     }

//     let decs = decimals[1];

//     for (let i = 0; i < decs.length; i++) {
//         if (count > 20) {
//             count = 0;
//             break;
//         }

//         if (decs.charAt(i) === '0') {
//             count++;
//         } else {
//             break;
//         }
//     }

//     if (count > decs.length) {
//         count--;
//     }

//     decs = decs.substring(0, count);
//     const result: string = decimals[0] + "." + decs;

//     if (result.endsWith('.')) {
//         return decimals[0];
//     }

//     return result;
// }

export function handleDecimal(price: string): string {
    return price;
}