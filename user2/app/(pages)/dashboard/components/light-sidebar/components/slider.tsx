'use client';

import { Fragment } from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
// Import Swiper styles
// @ts-ignore: swiper CSS module declaration missing
import 'swiper/css';
// @ts-ignore: swiper CSS module declaration missing
import 'swiper/css/pagination';
import '@/css/components/swiper.css';
import Image from 'next/image';
import Link from 'next/link';
import { Autoplay, Navigation, Pagination } from 'swiper/modules';

interface IEntryCalloutProps {
  className: string;
}

interface Props {
  image: string;
  url: string;
}

const DashboardSlider = ({ slideImages }: { slideImages: Props[] }) => {
  return (
    <Fragment>
      <Swiper
        pagination={true}
        modules={[Pagination, Navigation, Autoplay]}
        loop={true}
        autoplay={{
          delay: 2500,
          disableOnInteraction: false,
        }}
        navigation={true}
        className="mySwiper"
      >
        {slideImages?.map((slide, i) => (
          <SwiperSlide key={i}>
            <Link
              target="_blank"
              href={slide.url ?? '/'}
              className="rounded-lg h-full w-full "
            >
              <Image
                src={slide.image}
                width={838}
                height={256}
                className="!object-fill md:object-cover rounded-lg"
                alt="banner"
              />
            </Link>
          </SwiperSlide>
        ))}
        {slideImages?.length === 0 && (
          <>
            <SwiperSlide>
              <Image
                src="/media/banners/banner-2.png"
                width={838}
                height={320}
                className="!object-fill md:object-cover rounded-lg"
                alt="banner"
              />
            </SwiperSlide>
            <SwiperSlide>
              <Image
                src="/media/banners/banner-3.png"
                width={838}
                height={320}
                className="!object-fill md:object-cover rounded-lg"
                alt="banner"
              />
            </SwiperSlide>
          </>
        )}
      </Swiper>
    </Fragment>
  );
};

export { DashboardSlider, type IEntryCalloutProps };
