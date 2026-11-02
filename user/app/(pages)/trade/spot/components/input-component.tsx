interface Props {
  symbol?: string;
  children: React.ReactNode;
}

export const InputComponent = ({ symbol, children }: Props) => {
  return (
    <div className="relative">
      {children}
      <span className="absolute top-[34px] right-2 text-xs font-medium">
        {symbol}
      </span>
    </div>
  );
};
