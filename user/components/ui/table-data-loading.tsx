import { DataLoading } from './data-loading';

export const TableDataLoading = () => {
  return (
    <tr>
      <td className="text-center text-muted-foreground py-6">
        <DataLoading />
      </td>
    </tr>
  );
};
